<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Guest;
use App\Http\Requests\ReservationRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::with(['guest', 'room'])->orderBy('check_in_date', 'desc')->paginate(15);
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guests = Guest::orderBy('full_name')->get();
        $rooms = Room::where('status', 'available')->orderBy('room_number')->get();
        return view('reservations.create', compact('guests', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request)
    {
        $validated = $request->validated();

        // Calculate nights and total amount
        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);
        $numNights = $checkOut->diffInDays($checkIn);
        $room = Room::findOrFail($validated['room_id']);
        $totalAmount = $numNights * $room->price_per_night;

        $validated['num_nights'] = $numNights;
        $validated['total_amount'] = $totalAmount;

        Reservation::create($validated);

        // Update room status if reservation is confirmed
        if ($validated['status'] === 'confirmed') {
            $room->update(['status' => 'occupied']);
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['guest', 'room', 'payments']);
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        $guests = Guest::orderBy('full_name')->get();
        $rooms = Room::orderBy('room_number')->get();
        return view('reservations.edit', compact('reservation', 'guests', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $validated = $request->validated();

        // Calculate nights and total amount
        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);
        $numNights = $checkOut->diffInDays($checkIn);
        $room = Room::findOrFail($validated['room_id']);
        $totalAmount = $numNights * $room->price_per_night;

        $validated['num_nights'] = $numNights;
        $validated['total_amount'] = $totalAmount;

        $oldRoomId = $reservation->room_id;
        $oldStatus = $reservation->status;
        $newRoomId = $validated['room_id'];
        $newStatus = $validated['status'];

        $reservation->update($validated);

        // Update room statuses based on changes
        if ($oldRoomId !== $newRoomId) {
            // Update old room
            $oldRoom = Room::find($oldRoomId);
            if ($oldRoom && !$oldRoom->reservations()->where('status', 'confirmed')->where('check_out_date', '>=', today())->exists()) {
                $oldRoom->update(['status' => 'available']);
            }
        }

        // Update new room status
        if ($newStatus === 'confirmed') {
            Room::find($newRoomId)->update(['status' => 'occupied']);
        } elseif ($newStatus === 'cancelled' || $newStatus === 'completed') {
            $newRoom = Room::find($newRoomId);
            if ($newRoom && !$newRoom->reservations()->where('status', 'confirmed')->where('check_out_date', '>=', today())->exists()) {
                $newRoom->update(['status' => 'available']);
            }
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $room = $reservation->room;
        
        if ($reservation->payments()->exists()) {
            return redirect()->route('reservations.index')->with('error', 'Cannot delete reservation with existing payments.');
        }

        $reservation->delete();

        // Update room status if no more confirmed reservations
        if ($room && !$room->reservations()->where('status', 'confirmed')->where('check_out_date', '>=', today())->exists()) {
            $room->update(['status' => 'available']);
        }

        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully.');
    }

    public function apiIndex()
    {
        $reservations = Reservation::with(['guest', 'room'])
            ->get()
            ->map(function($reservation) {
                return [
                    'id' => $reservation->id,
                    'guest_name' => $reservation->guest->full_name,
                    'room_number' => $reservation->room->room_number,
                    'room_type' => $reservation->room->room_type,
                    'check_in_date' => $reservation->check_in_date,
                    'check_out_date' => $reservation->check_out_date,
                    'num_nights' => $reservation->num_nights,
                    'total_amount' => $reservation->total_amount,
                    'status' => $reservation->status,
                ];
            });
        return response()->json($reservations);
    }
}
