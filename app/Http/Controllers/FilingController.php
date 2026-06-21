<?php

namespace App\Http\Controllers;

use App\Exports\FilingsExport;
use App\Mail\CanceledFiling;
use App\Models\Filing;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class FilingController extends Controller
{
    private function normalizeText(string $text): string
    {
        $text = mb_strtolower($text);
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);

        return preg_replace('/\s+/', ' ', trim($text));
    }

    private function filteredFilingsQuery(Request $request)
    {
        return Filing::with(['editions', 'tags'])
            ->when($request->filled('id'), fn ($q) => $q->where('id', $request->input('id')))
            ->when($request->filled('macroarea'), fn ($q) => $q->whereIn('macroarea', (array) $request->input('macroarea')))
            ->when($request->filled('province'), fn ($q) => $q->whereIn('province', (array) $request->input('province')))
            ->when($request->filled('city'), fn ($q) => $q->where('city', 'LIKE', '%'.$request->input('city').'%'))
            ->when($request->filled('min_year'), fn ($q) => $q->where('min_year', '>=', $request->input('min_year')))
            ->when($request->filled('max_year'), fn ($q) => $q->where('max_year', '<=', $request->input('max_year')))
            ->when($request->boolean('is_certain_date'), fn ($q) => $q->where('is_certain_date', 1))
            ->when($request->boolean('is_sacred_dedication'), fn ($q) => $q->where('is_sacred_dedication', 1))
            ->when($request->filled('religion'), fn ($q) => $q->where('religion', $request->input('religion')))
            ->when($request->filled('corpus') || $request->filled('volume') || $request->filled('number_inscription'),
                fn ($q) => $q->whereHas('editions', fn ($eq) => $eq
                    ->when($request->filled('corpus'), fn ($eq) => $eq->where('corpus', 'LIKE', '%'.$request->input('corpus').'%'))
                    ->when($request->filled('volume'), fn ($eq) => $eq->where('volume', 'LIKE', '%'.$request->input('volume').'%'))
                    ->when($request->filled('number_inscription'), fn ($eq) => $eq->where('number_inscription', $request->input('number_inscription')))
                )
            )
            ->when($request->filled('tags'), function ($q) use ($request) {
                foreach ($request->input('tags') as $tagId) {
                    $q->whereHas('tags', fn ($tq) => $tq->where('tags.id', $tagId));
                }
            })
            ->when($request->has('people'), function ($q) use ($request) {
                foreach ((array) $request->input('people') as $row) {
                    $active = array_filter((array) $row, fn ($v) => $v !== '' && $v !== null);
                    if (empty($active)) {
                        continue;
                    }
                    $q->whereHas('people', fn ($pq) => $pq
                        ->when(!empty($row['praenomen']), fn ($pq) => $pq->where('praenomen', 'LIKE', '%'.$row['praenomen'].'%'))
                        ->when(!empty($row['nomen']),     fn ($pq) => $pq->where('nomen',     'LIKE', '%'.$row['nomen'].'%'))
                        ->when(!empty($row['cognomen']),  fn ($pq) => $pq->where('cognomen',  'LIKE', '%'.$row['cognomen'].'%'))
                        ->when(!empty($row['TM_PER_id']), fn ($pq) => $pq->where('TM_PER_id', $row['TM_PER_id']))
                    );
                }
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $rows = array_values(array_filter(
                    $request->input('search'),
                    fn ($row) => ! empty($row['term'])
                ));
                if (empty($rows)) {
                    return;
                }

                $q->where(function ($sub) use ($rows) {
                    foreach ($rows as $i => $row) {
                        $term       = $this->normalizeText($row['term']);
                        $within     = isset($row['within']) && $row['within'] !== '' ? (int) $row['within'] : null;
                        $operator   = $row['operator'] ?? 'AND';
                        $exact      = ! empty($row['exact']);
                        $normalized = "regexp_replace(lower(text), '[^a-z0-9 ]', '', 'g')";

                        if ($exact) {
                            // Space-pad so LIKE '% term %' only matches whole words.
                            $textExpr = $within
                                ? "CONCAT(' ', SUBSTRING($normalized, 1, ?), ' ')"
                                : "CONCAT(' ', $normalized, ' ')";
                            $pattern  = '% ' . $term . ' %';
                        } else {
                            $textExpr = $within
                                ? "SUBSTRING($normalized, 1, ?)"
                                : $normalized;
                            $pattern  = '%' . $term . '%';
                        }

                        $bindings = $within ? [$within, $pattern] : [$pattern];

                        if ($operator === 'NOT') {
                            $sub->whereRaw("$textExpr NOT LIKE ?", $bindings);
                        } elseif ($operator === 'OR' && $i > 0) {
                            $sub->orWhereRaw("$textExpr LIKE ?", $bindings);
                        } else {
                            $sub->whereRaw("$textExpr LIKE ?", $bindings);
                        }
                    }
                });
            });
    }

    public function index(Request $request)
    {
        $tags = Tag::all();
        $pairedTags = $this->buildTagPairs($tags);

        $filings = $this->filteredFilingsQuery($request)->orderBy('updated_at', 'desc')->paginate(100);

        return view('pages.filings.index', compact('filings', 'pairedTags', 'tags'));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new FilingsExport($this->filteredFilingsQuery($request)),
            'filings.xlsx'
        );
    }

    public function show(Filing $filing)
    {
        $filing->load(['tags', 'editions', 'people', 'externalResources']);
        $groupedTags = $filing->tags->groupBy('category');
        $previousId = Filing::where('id', '<', $filing->id)
            ->orderBy('id', 'desc')
            ->value('id');

        $nextId = Filing::where('id', '>', $filing->id)
            ->orderBy('id', 'asc')
            ->value('id');

        return view('pages.filings.show', compact('filing', 'groupedTags', 'previousId', 'nextId'));
    }

    public function destroy(Filing $filing, Request $request)
    {
        $filing->deletion_notes = $request->deletion_notes;
        $filing->save();

        foreach ($filing->editions as $edition) {
            if ($edition->edition_image) {
                Storage::delete($edition->edition_image);
            }
        }

        foreach ($filing->proposals as $proposal) {
            $proposal->delete();
        }

        Mail::to($filing->proposedBy)->send(new CanceledFiling($filing));

        $filing->editions()->delete();
        $filing->people()->delete();
        $filing->externalResources()->delete();
        $filing->tags()->detach();
        $filing->delete();
        return redirect()->route('filings.index');
    }
}
