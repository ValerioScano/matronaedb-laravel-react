<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filing;

class PublicPageController extends Controller
{
    public function welcome() {
        return view('pages.publicpages.welcome');
    }

    public function index() {
        $filings = Filing::orderBy('created_at', 'desc')->with(['editions', 'tags'])->paginate(20);
        return view('pages.publicpages.index', compact('filings'));
    }

    public function show(Filing $filing) {
        $filing->load(['tags', 'editions']);
        return view('pages.publicpages.show', compact('filing'));
    } 
}
