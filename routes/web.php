<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

// // Rotte pubbliche — accessibili a tutti
// Route::controller(PublicPageController::class)->group(function () {
//     Route::get('/', 'welcome')->name('welcome');
//     Route::get('/filings', 'index')->name('filings.index');
//     Route::get('/filings/{filing}', 'show')->name('filings.show');
// });

// Rotte per utenti autenticati
Route::middleware(['auth', 'verified'])
    ->prefix('my')
    ->name('my.')->group(function () {

        // Profilo
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');

        // Filings
        Route::controller(ProposalController::class)
            ->prefix('proposals')
            ->name('proposals.')
            ->group(function () {
                Route::get('/', 'index')->name('index');                        // lista proprie proposals
                Route::get('/create', 'create')->name('create');                // compila nuova schedatura
                Route::post('/', 'store')->name('store');                       // salva nuova schedatura
                Route::get('/{proposal}', 'show')->name('show');                // mostra propria proposal
                Route::get('/{proposal}/edit', 'edit')->name('edit');           // form modifica proposal pendente
                Route::put('/{proposal}', 'update')->name('update');            // aggiorna proposal pendente
                Route::delete('/{proposal}', 'destroy')->name('destroy');       // elimina proposal pendente
            });

        // Revisions — proposte di modifica di filing esistenti (filing_id valorizzato)
        Route::controller(ProposalController::class)
            ->prefix('filings/{filing}/revisions')
            ->name('revisions.')
            ->group(function () {
                Route::get('/create', 'createRevision')->name('create');        // form revisione filing
                Route::post('/', 'storeRevision')->name('store');               // salva revisione
            });
    });

// Rotte solo admin
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');

            // Gestione proposals (nuove schedature)
            Route::prefix('proposals')->name('proposals.')->group(function () {
                Route::get('/pending', 'pendingProposals')->name('pending');
                Route::patch('/{proposal}/approve', 'approve')->name('approve');
                Route::patch('/{proposal}/reject', 'reject')->name('reject');
            });

            // Gestione filings approvati
            Route::prefix('filings')->name('filings.')->group(function () {
                Route::get('/', 'indexFilings')->name('index');
                Route::get('/{filing}', 'showFiling')->name('show');
                Route::delete('/{filing}', 'destroyFiling')->name('destroy');
            });

            // Gestione users
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', 'indexUsers')->name('index');
                Route::patch('/{user}/role', 'updateRole')->name('updateRole');
                Route::delete('/{user}', 'destroyUser')->name('destroy');
                Route::delete('/{user}/with-records', 'destroyUserWithRecords')->name('destroyWithRecords');
            });
        });
    });

require __DIR__.'/auth.php';
