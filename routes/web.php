<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\FilingController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Rotte pubbliche — accessibili a tutti
Route::controller(PublicPageController::class)->group(function () {
    Route::get('/', 'welcome')->name('welcome');
    Route::get('/filings', 'index')->name('filings.index');
    Route::get('/filings/{id}', 'show')->name('filings.show');
});

// Rotte per utenti autenticati
Route::middleware(['auth', 'verified'])->group(function () {

    // Profilo
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Filings
    Route::controller(FilingController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/filings/create', 'create')->name('filings.create');
        Route::post('/filings', 'store')->name('filings.store');
        Route::get('/filings/{id}/edit', 'edit')->name('filings.edit');
        Route::put('/filings/{id}', 'update')->name('filings.update');
        Route::delete('/filings/{id}', 'destroy')->name('filings.destroy');
    });

    // Revisions
    Route::controller(RevisionController::class)->group(function () {
        Route::get('/filings/{id}/revisions/create', 'create')->name('revisions.create');
        Route::post('/filings/{id}/revisions', 'store')->name('revisions.store');
        Route::get('/filings/{id}/revisions/{revisionId}/edit', 'edit')->name('revisions.edit');
        Route::put('/filings/{id}/revisions/{revisionId}', 'update')->name('revisions.update');
        Route::delete('/filings/{id}/revisions/{revisionId}', 'destroy')->name('revisions.destroy');
    });
});

// Rotte solo admin
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/filings/pending', 'pendingFilings')->name('filings.pending');
            Route::patch('/filings/{id}/approve', 'approveFiling')->name('filings.approve');
            Route::patch('/filings/{id}/reject', 'rejectFiling')->name('filings.reject');
            Route::get('/revisions/pending', 'pendingRevisions')->name('revisions.pending');
            Route::patch('/revisions/{id}/approve', 'approveRevision')->name('revisions.approve');
            Route::patch('/revisions/{id}/reject', 'rejectRevision')->name('revisions.reject');
        });
    });

require __DIR__.'/auth.php';