<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);

        return view('pages.users.index', compact('users'));
    }

    // Modify role from registered_user to admin

    public function updateRole(User $user)
    {
        $user->role = $user->role === 'admin' ? 'registered_user' : 'admin';
        $user->save();

        return redirect()->route('users.index');
    }

    public function destroyUser(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }

    public function destroyUserWithRecords(User $user)
    {
        $user->filings()->delete();
        $user->proposals()->delete();
        $user->delete();

        return redirect()->route('users.index');
    }

}
