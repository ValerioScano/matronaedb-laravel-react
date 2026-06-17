<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tagPairs = $this->buildTagPairs(Tag::orderBy('category')->orderBy('name')->get());

        return view('pages.tags.index', compact('tagPairs'));
    }

    public function create()
    {
        $categories = Tag::distinct()->orderBy('category')->pluck('category');

        return view('pages.tags.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255|unique:tags,name',
            'label'    => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        // Create the base tag and its uncertain variant in one operation
        Tag::create($data);
        Tag::create([
            'name'     => $data['name'] . '?',
            'label'    => $data['label'] . '?',
            'category' => $data['category'],
        ]);

        return redirect()->route('tags.index')->with('success', 'Tag pair created successfully.');
    }

    public function edit(Tag $tag)
    {
        // Always edit the base tag (without '?')
        if (str_ends_with($tag->name, '?')) {
            $base = Tag::where('name', rtrim($tag->name, '?'))->firstOrFail();
            return redirect()->route('tags.edit', $base);
        }

        $categories  = Tag::distinct()->orderBy('category')->pluck('category');
        $uncertainTag = Tag::where('name', $tag->name . '?')->first();

        return view('pages.tags.edit', compact('tag', 'uncertainTag', 'categories'));
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'label'    => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        $uncertainTag = Tag::where('name', $tag->name . '?')->first();

        $tag->update($data);

        if ($uncertainTag) {
            $uncertainTag->update([
                'name'     => $data['name'] . '?',
                'label'    => $data['label'] . '?',
                'category' => $data['category'],
            ]);
        }

        return redirect()->route('tags.index')->with('success', 'Tag pair updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        // Delete both the base and uncertain variant
        $uncertainTag = Tag::where('name', $tag->name . '?')->first();

        $tag->delete();
        $uncertainTag?->delete();

        return redirect()->route('tags.index')->with('success', 'Tag pair deleted successfully.');
    }
}
