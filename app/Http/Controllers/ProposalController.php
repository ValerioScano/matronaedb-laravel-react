<?php

namespace App\Http\Controllers;

use App\Models\Edition;
use App\Models\Filing;
use App\Models\Proposal;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_proposals = Proposal::where('proposed_by', Auth::id())->orderBy('updated_at', 'desc')->paginate(20);

        return view('pages.userpages.user_proposals', compact('user_proposals'));
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['editions']);
        $groupedTags = $proposal->tags->groupBy('category');

        return view('pages.userpages.proposal_show', compact('proposal', 'groupedTags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        $groupedTags = $tags->groupBy('category');

        return view('pages.userpages.create_proposal', compact('groupedTags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $proposal = new Proposal;
        $proposal->text = $data['text'];
        $proposal->region = $data['region'];
        $proposal->province = $data['province'];
        $proposal->city = $data['city'] ?? null;
        $proposal->min_year = $data['min_year'] ?? null;
        $proposal->max_year = $data['max_year'] ?? null;
        $proposal->is_certain_date = array_key_exists('is_certain_date', $data) ? 1 : 0;
        $proposal->is_sacred_dedication = array_key_exists('is_sacred_dedication', $data) ? 1 : 0;
        $proposal->religion = $data['religion'];
        $proposal->notes = $data['notes'] ?? null;
        $proposal->proposed_by = Auth::id();
        $proposal->save();

        // 2. Salva le edizioni
        if (array_key_exists('editions', $data)) {
            foreach ($data['editions'] as $edition) {
                $editionData = [
                    'corpus' => $edition['corpus'] ?? null,
                    'volume' => $edition['volume'] ?? null,
                    'number_inscription' => $edition['number_inscription'] ?? null,
                    'publication_year' => $edition['publication_year'] ?? null,
                    'corpus_page' => $edition['corpus_page'] ?? null,
                    'last_name_author' => $edition['last_name_author'] ?? null,
                ];

                if (array_key_exists('edition_image', $edition)) {
                    $editionData['edition_image'] = Storage::putFile('editions', $edition['edition_image']);
                }

                $proposal->editions()->create($editionData);
            }
        }

        // 3. Salva i tags
        if (array_key_exists('tags', $data)) {
            $proposal->tags()->sync($data['tags']);
        }

        return redirect()->route('my.proposals.show', $proposal);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proposal $proposal)
    {
        $proposal->load('editions');
        $tags = Tag::all();
        $groupedTags = $tags->groupBy('category');

        return view('pages.userpages.modify_proposal', compact('proposal', 'groupedTags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proposal $proposal)
    {
        $data = $request->all();

        // 1. Aggiorna la proposal
        $proposal->text = $data['text'];
        $proposal->region = $data['region'];
        $proposal->province = $data['province'];
        $proposal->city = $data['city'] ?? null;
        $proposal->min_year = $data['min_year'] ?? null;
        $proposal->max_year = $data['max_year'] ?? null;
        $proposal->is_certain_date = array_key_exists('is_certain_date', $data) ? 1 : 0;
        $proposal->is_sacred_dedication = array_key_exists('is_sacred_dedication', $data) ? 1 : 0;
        $proposal->religion = $data['religion'];
        $proposal->notes = $data['notes'] ?? null;
        $proposal->status = 'pending';
        $proposal->save();

        // 2. Aggiorna le edizioni
        if (array_key_exists('editions', $data)) {
            foreach ($data['editions'] as $edition) {

                $editionData = [
                    'corpus' => $edition['corpus'] ?? null,
                    'volume' => $edition['volume'] ?? null,
                    'number_inscription' => $edition['number_inscription'] ?? null,
                    'publication_year' => $edition['publication_year'] ?? null,
                    'corpus_page' => $edition['corpus_page'] ?? null,
                    'last_name_author' => $edition['last_name_author'] ?? null,
                ];

                if (array_key_exists('edition_image', $edition)) {
                    if (isset($edition['id'])) {
                        $oldEdition = Edition::find($edition['id']);
                        if ($oldEdition->edition_image) {
                            Storage::delete($oldEdition->edition_image);
                        }
                    }
                    $editionData['edition_image'] = Storage::putFile('editions', $edition['edition_image']);
                }

                if (isset($edition['id'])) {
                    // Aggiorna edizione esistente
                    Edition::find($edition['id'])->update($editionData);
                } else {
                    // Crea nuova edizione collegata alla proposal
                    $proposal->editions()->create($editionData);
                }
            }
        }
        // 3. Aggiorna i tags
        if (array_key_exists('tags', $data)) {
            $proposal->tags()->sync($data['tags']);
        } else {
            $proposal->tags()->detach();
        }

        return redirect()->route('my.proposals.show', $proposal);
    }

    public function destroy(Proposal $proposal)
    {
        foreach ($proposal->editions as $edition) {
            if ($edition->edition_image) {
                Storage::delete($edition->edition_image);
            }
        }
        $proposal->editions()->delete();
        $proposal->tags()->detach();
        $proposal->delete();

        return redirect()->route('my.proposals.index');
    }

    public function createRevision(Filing $filing)
    {
        $filing->load('editions');
        $tags = Tag::all();
        $groupedTags = $tags->groupBy('category');

        return view('pages.userpages.create_revision', compact('filing', 'groupedTags'));
    }

    public function storeRevision(Request $request, Filing $filing)
    {
        $data = $request->all();

        // 1. Crea la nuova proposal con filing_id valorizzato
        $proposal = new Proposal;
        $proposal->filing_id = $filing->id;
        $proposal->proposed_by = Auth::id();
        $proposal->text = $data['text'];
        $proposal->region = $data['region'];
        $proposal->province = $data['province'];
        $proposal->city = $data['city'] ?? null;
        $proposal->min_year = $data['min_year'] ?? null;
        $proposal->max_year = $data['max_year'] ?? null;
        $proposal->is_certain_date = array_key_exists('is_certain_date', $data) ? 1 : 0;
        $proposal->is_sacred_dedication = array_key_exists('is_sacred_dedication', $data) ? 1 : 0;
        $proposal->religion = $data['religion'];
        $proposal->notes = $data['notes'] ?? null;
        $proposal->save();

        // 2. Salva le edizioni
        if (array_key_exists('editions', $data)) {
            foreach ($data['editions'] as $edition) {
                $editionData = [
                    'corpus' => $edition['corpus'] ?? null,
                    'volume' => $edition['volume'] ?? null,
                    'number_inscription' => $edition['number_inscription'] ?? null,
                    'publication_year' => $edition['publication_year'] ?? null,
                    'corpus_page' => $edition['corpus_page'] ?? null,
                    'last_name_author' => $edition['last_name_author'] ?? null,
                ];

                if (array_key_exists('edition_image', $edition)) {
                    $editionData['edition_image'] = Storage::putFile('editions', $edition['edition_image']);
                }

                $proposal->editions()->create($editionData);
            }
        }

        // 3. Salva i tags
        if (array_key_exists('tags', $data)) {
            $proposal->tags()->sync($data['tags']);
        }

        return redirect()->route('my.proposals.show', $proposal);
    }
}
