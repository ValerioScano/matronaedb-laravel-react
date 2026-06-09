<?php

namespace App\Http\Controllers;

use App\Models\Filing;
use Illuminate\Support\Facades\Storage;

class FilingController extends Controller
{
    public function index()
    {
        $filings = Filing::orderBy('created_at', 'desc')->with(['editions', 'tags'])->paginate(20);

        return view('pages.filings.index', compact('filings'));
    }

    public function show(Filing $filing)
    {
        $filing->load(['tags', 'editions']);
        $groupedTags = $filing->tags->groupBy('category');
        $previousId = Filing::where('id', '<', $filing->id)
            ->orderBy('id', 'desc')
            ->value('id');

        $nextId = Filing::where('id', '>', $filing->id)
            ->orderBy('id', 'asc')
            ->value('id');

        return view('pages.filings.show', compact('filing', 'groupedTags', 'previousId', 'nextId'));
    }

    public function destroy(Filing $filing)
    {
        foreach ($filing->editions as $edition) {
            if ($edition->edition_image) {
                Storage::delete($edition->edition_image);
            }
        }
        $filing->editions()->delete();
        $filing->tags()->detach();
        $filing->delete();

        return redirect()->route('filings.index');

    }
}
