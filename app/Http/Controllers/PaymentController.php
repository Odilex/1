<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with(['reservation.guest', 'reservation.room'])->orderBy('payment_date', 'desc')->paginate(15);
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $reservationId = $request->get('reservation_id');
        $reservations = Reservation::with(['guest', 'room'])->get();
        
        if ($reservationId) {
            $selectedReservation = Reservation::with(['guest', 'room', 'payments'])->find($reservationId);
        } else {
            $selectedReservation = null;
        }
        
        return view('payments.create', compact('reservations', 'selectedReservation'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer',
            'payment_date' => 'required|date|before_or_equal:now',
            'reference_number' => 'nullable|string|max:60|unique:payments',
            'notes' => 'nullable|string'
        ]);

        $reservation = Reservation::findOrFail($validated['reservation_id']);
        
        // Check if payment would exceed the total amount
        $currentPaid = $reservation->payments()->sum('amount_paid');
        $remainingBalance = $reservation->total_amount - $currentPaid;
        
        if ($validated['amount_paid'] > $remainingBalance) {
            return redirect()->back()->withInput()->with('error', 'Payment amount (RWF ' . number_format($validated['amount_paid'], 2) . ') exceeds remaining balance (RWF ' . number_format($remainingBalance, 2) . ').');
        }

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['reservation.guest', 'reservation.room']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $payment->load(['reservation.guest', 'reservation.room']);
        $reservations = Reservation::with(['guest', 'room'])->get();
        return view('payments.edit', compact('payment', 'reservations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,mobile_money,bank_transfer',
            'payment_date' => 'required|date|before_or_equal:now',
            'reference_number' => 'nullable|string|max:60|unique:payments,reference_number,' . $payment->id,
            'notes' => 'nullable|string'
        ]);

        $reservation = Reservation::findOrFail($validated['reservation_id']);
        
        // Check if payment would exceed the total amount (excluding current payment)
        $currentPaid = $reservation->payments()->where('id', '!=', $payment->id)->sum('amount_paid');
        $remainingBalance = $reservation->total_amount - $currentPaid;
        
        if ($validated['amount_paid'] > $remainingBalance) {
            return redirect()->back()->withInput()->with('error', 'Payment amount (RWF ' . number_format($validated['amount_paid'], 2) . ') exceeds remaining balance (RWF ' . number_format($remainingBalance, 2) . ').');
        }

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function apiIndex()
    {
        $payments = Payment::with(['reservation.guest', 'reservation.room'])
            ->orderBy('payment_date', 'desc')
            ->get()
            ->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'reservation_id' => $payment->reservation_id,
                    'guest_name' => $payment->reservation->guest->full_name,
                    'room_number' => $payment->reservation->room->room_number,
                    'amount_paid' => $payment->amount_paid,
                    'payment_method' => $payment->payment_method,
                    'payment_date' => $payment->payment_date,
                    'reference_number' => $payment->reference_number,
                ];
            });

        $stats = [
            'totalPayments' => Payment::count(),
            'totalRevenue' => Payment::sum('amount_paid'),
            'avgPayment' => Payment::avg('amount_paid'),
        ];

        return response()->json([
            'payments' => $payments,
            'stats' => $stats
        ]);
    }
}
