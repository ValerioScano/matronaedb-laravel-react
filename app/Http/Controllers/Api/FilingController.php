<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Filing;

class FilingController extends Controller
{
    public function welcome()
    {
        // return view('pages.publicpages.welcome');
    }

    public function index()
    {
        $filings = Filing::orderBy('created_at', 'desc')->with(['editions', 'tags'])->paginate(20);

        return response()->json($filings);

    }

    public function show(Filing $filing)
    {
        $filing->load(['tags', 'editions']);

        return response()->json($filing);
    }
}
