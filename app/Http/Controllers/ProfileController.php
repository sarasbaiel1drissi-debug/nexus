<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'bio' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'region' => ['nullable', 'string', 'max:100'],
            'languages' => ['nullable', 'string', 'max:255'],
            'availability' => ['nullable', 'string', 'max:255'],
            'playstyle_tags' => ['nullable', 'string', 'max:500'],
            'cover_position' => ['nullable', 'string', 'max:50'],
            'theme_color' => ['nullable', 'string', 'max:20'],
            'theme_bg' => ['nullable', 'string', 'max:20'],
            'theme_text' => ['nullable', 'string', 'max:20'],
            'theme_bg_image' => ['nullable', 'url', 'max:500'],
            'profile_position' => ['nullable', 'string', 'max:50'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'cover_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        // Basic fields
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'bio' => $request->bio,
            'location' => $request->location,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'region' => $request->region,
            'languages' => $request->languages,
            'availability' => $request->availability,
            'cover_position' => $request->cover_position ?? 'center',
            'profile_position' => $request->profile_position ?? '50% 50%',
        ]);

        // Playstyle tags (comma-separated → array)
        if ($request->playstyle_tags) {
            $tags = array_map('trim', explode(',', $request->playstyle_tags));
            $user->playstyle_tags = array_filter($tags);
        } else {
            $user->playstyle_tags = null;
        }

        // Theme colors (or reset)
        if ($request->has('reset_theme')) {
            $user->theme_color = null;
            $user->theme_bg = null;
            $user->theme_text = null;
            $user->theme_bg_image = null;
        } else {
            $user->theme_color = $request->theme_color;
            $user->theme_bg = $request->theme_bg;
            $user->theme_text = $request->theme_text;
            $user->theme_bg_image = $request->theme_bg_image;
        }

        // Profile picture upload / removal
        if ($request->has('remove_profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = null;
        } elseif ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $user->profile_picture = $request->file('profile_picture')->store('profiles', 'public');
        }

        // Cover picture upload
        if ($request->hasFile('cover_picture')) {
            if ($user->cover_picture) {
                Storage::disk('public')->delete($user->cover_picture);
            }
            $user->cover_picture = $request->file('cover_picture')->store('covers', 'public');
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully ✨');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
