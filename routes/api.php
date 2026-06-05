<?php

use App\Http\Controllers\Api\FilingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rotte pubbliche - api

Route::controller(FilingController::class)->group(function () {
    Route::get('/', 'welcome');
    Route::get('/filings', 'index');
    Route::get('/filings/{filing}', 'show');
});
