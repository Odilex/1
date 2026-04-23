<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guests = Guest::paginate(15);
        return view('guests.index', compact('guests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:guests',
            'phone' => 'required|string|max:20',
            'nationality' => 'nullable|string|max:80',
            'id_number' => 'required|string|max:50|unique:guests'
        ]);

        Guest::create($validated);
        return redirect()->route('guests.index')->with('success', 'Guest registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guest $guest)
    {
        $guest->load('reservations.room');
        return view('guests.show', compact('guest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Guest $guest)
    {
        return view('guests.edit', compact('guest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:guests,email,' . $guest->id,
            'phone' => 'required|string|max:20',
            'nationality' => 'nullable|string|max:80',
            'id_number' => 'required|string|max:50|unique:guests,id_number,' . $guest->id
        ]);

        $guest->update($validated);
        return redirect()->route('guests.index')->with('success', 'Guest updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Guest $guest)
    {
        // Check if guest has reservations
        if ($guest->reservations()->exists()) {
            return redirect()->route('guests.index')->with('error', 'Cannot delete guest with existing reservations.');
        }

        $guest->delete();
        return redirect()->route('guests.index')->with('success', 'Guest deleted successfully.');
    }

    public function apiIndex()
    {
        $guests = Guest::all();
        return response()->json($guests);
    }
}
