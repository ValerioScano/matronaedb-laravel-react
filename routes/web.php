<?php

use App\Http\Controllers\FilingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Welcome
Route::view('/', 'pages.welcome')->name('welcome');

Route::view('/esercizio', 'pages.esercizio');

// Dashboard
Route::get('/dashboard', [ProfileController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Filings
Route::prefix('filings')->name('filings.')->group(function () {
    Route::get('/', [FilingController::class, 'index'])->name('index');
    Route::get('/{filing}', [FilingController::class, 'show'])->name('show');
    Route::delete('/{filing}', [FilingController::class, 'destroy'])
        ->middleware(['auth', 'verified', 'admin'])
        ->name('destroy');
});

// Proposals + Revisions
Route::prefix('proposals')->name('proposals.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ProposalController::class, 'index'])->name('index');
    Route::get('/create', [ProposalController::class, 'create'])->name('create');
    Route::post('/', [ProposalController::class, 'store'])->name('store');
    Route::get('/pending', [ProposalController::class, 'pending'])
        ->middleware('admin')
        ->name('pending');
    Route::get('/{proposal}', [ProposalController::class, 'show'])->name('show');
    Route::get('/{proposal}/edit', [ProposalController::class, 'edit'])->name('edit');
    Route::put('/{proposal}', [ProposalController::class, 'update'])->name('update');
    Route::delete('/{proposal}', [ProposalController::class, 'destroy'])->name('destroy');
    Route::patch('/{proposal}/approve', [ProposalController::class, 'approve'])
        ->middleware('admin')
        ->name('approve');
    Route::patch('/{proposal}/reject', [ProposalController::class, 'reject'])
        ->middleware('admin')
        ->name('reject');
    // Revisions
    Route::get('/filings/{filing}/create', [ProposalController::class, 'createRevision'])->name('revisions.create');
    Route::post('/filings/{filing}', [ProposalController::class, 'storeRevision'])->name('revisions.store');
});

// Tags
Route::prefix('tags')->name('tags.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', [TagController::class, 'index'])->name('index');
    Route::get('/create', [TagController::class, 'create'])->name('create');
    Route::post('/', [TagController::class, 'store'])->name('store');
    Route::get('/{tag}/edit', [TagController::class, 'edit'])->name('edit');
    Route::put('/{tag}', [TagController::class, 'update'])->name('update');
    Route::delete('/{tag}', [TagController::class, 'destroy'])->name('destroy');
});

// Users
Route::prefix('users')->name('users.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::patch('/{user}/role', [UserController::class, 'updateRole'])->name('updateRole');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::delete('/{user}/with-records', [UserController::class, 'destroyWithRecords'])->name('destroyWithRecords');
});

// Profile
Route::prefix('profile')->name('profile.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

require __DIR__.'/auth.php';