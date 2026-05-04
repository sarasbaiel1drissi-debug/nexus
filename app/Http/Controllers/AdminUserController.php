<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Badge;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $users = User::with('badges')->get();
        return view('dashboard.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'birthdate' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
        ]);

        $newUserBadge = Badge::where('name', 'New User')->first();

        if($newUserBadge) {
            $user->badges()->attach($newUserBadge->id, [
                'earned_at' => now(),
                'expires_at' => now()->addDays(10),
            ]);

        $user->save();

        session()->flash('flash.message',"L'utilisateur " . $user->name . " a été ajouté avec succès");
        session()->flash('flash.type','success');

        return back();
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
        'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'birthdate' => ['nullable', 'date'],
        'gender' => ['nullable', 'in:male,female'],
        'password' => ['nullable', 'confirmed', Password::defaults()],
    ]);

        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->birthdate = $request->input('birthdate');
        $user->gender = $request->input('gender');

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

    $user->save();

    session()->flash('flash.message', "L'utilisateur " . $user->name . " a été mis à jour avec succès");
    session()->flash('flash.type', 'success');

    return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($user->delete()) {
            session()->flash('flash.message',"L'utilisateur " . $user->name . " a été supprimé");
            session()->flash('flash.type','success');
        } else {
            session()->flash('flash.message',"L'utilisateur " . $user->name . " n'a pas été supprimé");
            session()->flash('flash.type','danger');
        }

        return back();
    }
}
