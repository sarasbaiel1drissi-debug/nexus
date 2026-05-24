<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Services\BadgeService;

class RegisteredUserController extends Controller
{

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'birthdate' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            if ($request->input('register_context') === 'modal') {
                return back()
                    ->withErrors($validator, 'register')
                    ->withInput($request->except(['password', 'password_confirmation']))
                    ->with('open_register_modal', true);
            }

            throw new ValidationException($validator);
        }

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
            'is_online' => true,
        ]);

    // Award "New User" badge
    app(BadgeService::class)->awardNewUserBadge($user);

    event(new Registered($user));
    Auth::login($user);

    return redirect()->route('dashboard');
}


}
