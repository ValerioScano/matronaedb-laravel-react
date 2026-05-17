<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    public function welcome() {
        return view('welcome');
    }
}


// PublicPageController — pagine pubbliche:

// welcome()
// index() — lista iscrizioni
// show() — dettaglio iscrizione
