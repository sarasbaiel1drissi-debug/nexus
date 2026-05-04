<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;

class BadgeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $badges=Badge::all();
        return view('dashboard.badges.index', compact('badges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->filled('criteria') && is_string($request->criteria)) {
            $request->merge([
                'criteria' => json_decode($request->criteria, true),
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'criteria' => ['nullable', 'array'],
        ]);

        Badge::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'criteria' => $validated['criteria'] ?? null,
        ]);

        session()->flash('flash.message', 'Badge created successfully');
        session()->flash('flash.type', 'success');

        return back();
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
    public function update(Request $request, Badge $badge)
    {

        if ($request->filled('criteria') && is_string($request->criteria)) {
            $request->merge([
                'criteria' => json_decode($request->criteria, true),
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'criteria' => ['nullable', 'array'],
        ]);

        $badge->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'criteria' => $validated['criteria'] ?? null,
        ]);

    session()->flash('flash.message', 'Badge updated successfully');
    session()->flash('flash.type', 'success');

    return back();
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Badge $badge)
    {
        if($badge->delete()) {
            session()->flash('flash.message',"Le badge " . $badge->name . " a été supprimé");
            session()->flash('flash.type','success');
        } else {
            session()->flash('flash.message',"Le badge " . $badge->name . " n'a pas été supprimé");
            session()->flash('flash.type','danger');
        }

        return back();
    }
}
