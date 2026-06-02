<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filing;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function dashboard()
    {
        return view('pages.adminpages.admin_dashboard');
    }

    /**
     * Display list of all proposals.
     */
    public function pendingProposals(Proposal $proposal)
    {
        $user_proposals = Proposal::where('status', 'pending')->orderBy('created_at', 'desc')->paginate(20);

        return view('pages.adminpages.pending_proposals', compact('user_proposals'));
    }

    /**
     * Approve the filing.
     */
    public function approve(Proposal $proposal)
    {
        if ($proposal->status !== 'pending') {
            return redirect()->route('admin.proposals.pending')
                ->with('error', 'This proposal has already been processed.');
        }
        // 1. Crea o aggiorna il filing
        if ($proposal->filing_id) {
            // È una revisione — aggiorna il filing esistente
            $filing = Filing::find($proposal->filing_id);
        } else {
            // È una nuova schedatura — crea un nuovo filing
            $filing = new Filing;
        }

        // 2. Copia i campi dalla proposal al filing
        $filing->text = $proposal->text;
        $filing->region = $proposal->region;
        $filing->province = $proposal->province;
        $filing->city = $proposal->city;
        $filing->min_year = $proposal->min_year;
        $filing->max_year = $proposal->max_year;
        $filing->is_certain_date = $proposal->is_certain_date;
        $filing->is_sacred_dedication = $proposal->is_sacred_dedication;
        $filing->religion = $proposal->religion;
        $filing->notes = $proposal->notes;
        $filing->proposed_by = $proposal->proposed_by;
        $filing->approved_by = Auth::id();
        $filing->save();

        // 3. Copia le edizioni
        $filing->editions()->delete(); // elimina le vecchie se è una revisione
        foreach ($proposal->editions as $edition) {
            $filing->editions()->create([
                'corpus' => $edition->corpus,
                'volume' => $edition->volume,
                'number_inscription' => $edition->number_inscription,
                'publication_year' => $edition->publication_year,
                'corpus_page' => $edition->corpus_page,
                'last_name_author' => $edition->last_name_author,
                'edition_image' => $edition->edition_image,
            ]);
        }

        // 4. Copia i tags
        $filing->tags()->sync($proposal->tags->pluck('id'));

        // 5. Aggiorna lo stato della proposal
        $proposal->status = 'approved';
        $proposal->approved_by = Auth::id();
        $proposal->save();

        return redirect()->route('admin.proposals.pending');
    }

    /**
     * Reject the filing.
     */
    public function reject(Proposal $proposal, Request $request)
    {
        $proposal->status = 'rejected';
        $proposal->rejection_notes = $request->rejection_notes;
        $proposal->approved_by = Auth::id();
        $proposal->save();

        return redirect()->route('admin.proposals.pending');
    }

    // Displays all the users

    public function indexUsers()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('pages.adminpages.index_users', compact('users'));
    }

    // Modify role from registered_user to admin

    public function updateRole(User $user)
    {
        $user->role = $user->role === 'admin' ? 'registered_user' : 'admin';
        $user->save();

        return redirect()->route('admin.users.index');
    }

    public function destroyUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function destroyUserWithRecords(User $user)
    {
        $user->filings()->delete();
        $user->proposals()->delete();
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function indexFilings()
    {
        $filings = Filing::orderBy('created_at', 'desc')->paginate(20);

        return view('pages.adminpages.admin_index_filings', compact('filings'));
    }

    public function showFiling(Filing $filing)
    {
        $filing->load(['tags', 'editions']);

        return view('pages.adminpages.admin_show_filing', compact('filing'));
    }

    public function destroyFiling(Filing $filing)
    {
        foreach ($filing->editions as $edition) {
            if ($edition->edition_image) {
                Storage::delete($edition->edition_image);
            }
        }
        $filing->editions()->delete();
        $filing->tags()->detach();
        $filing->delete();

        return redirect()->route('admin.filings.index');

    }
}
