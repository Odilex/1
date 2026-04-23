<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\RoomRequest;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::paginate(15);
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomRequest $request)
    {
        Room::create($request->validated());
        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomRequest $request, Room $room)
    {
        $room->update($request->validated());
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        // Check if room has active reservations
        if ($room->reservations()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return redirect()->route('rooms.index')->with('error', 'Cannot delete room with active reservations.');
        }

        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }

    public function apiIndex()
    {
        $rooms = Room::all();
        return response()->json($rooms);
    }
}
