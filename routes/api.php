<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FilingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rotte pubbliche - api

Route::controller(FilingController::class)->group(function() {
    Route::get('/', 'welcome')->name('welcome');
    Route::get('/filings', 'index')->name('filings.index');
    Route::get('/filings/{filing}', 'show')->name('filings.show');
});
