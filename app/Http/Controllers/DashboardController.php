<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic counts
        $totalRooms = Room::count();
        $totalGuests = Guest::count();
        $totalReservations = Reservation::count();
        $totalPayments = Payment::count();

        // Room status breakdown
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();

        // Reservation status breakdown
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $confirmedReservations = Reservation::where('status', 'confirmed')->count();
        $completedReservations = Reservation::where('status', 'completed')->count();
        $cancelledReservations = Reservation::where('status', 'cancelled')->count();

        // Financial statistics
        $totalRevenue = Payment::sum('amount_paid');
        $totalReservationValue = Reservation::sum('total_amount');
        $outstandingBalance = $totalReservationValue - $totalRevenue;
        $averagePayment = Payment::avg('amount_paid');

        // Monthly statistics (current month)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $monthlyRevenue = Payment::whereMonth('payment_date', $currentMonth)
            ->whereYear('payment_date', $currentYear)
            ->sum('amount_paid');
            
        $monthlyReservations = Reservation::whereMonth('check_in_date', $currentMonth)
            ->whereYear('check_in_date', $currentYear)
            ->count();

        // Recent activity
        $recentReservations = Reservation::with(['guest', 'room'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentPayments = Payment::with(['reservation.guest', 'reservation.room'])
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        // Upcoming check-ins (next 7 days)
        $upcomingCheckins = Reservation::with(['guest', 'room'])
            ->where('status', 'confirmed')
            ->whereBetween('check_in_date', [Carbon::today(), Carbon::today()->addDays(7)])
            ->orderBy('check_in_date')
            ->limit(5)
            ->get();

        // Today's activity
        $todayCheckins = Reservation::whereDate('check_in_date', Carbon::today())
            ->where('status', 'confirmed')
            ->count();
            
        $todayCheckouts = Reservation::whereDate('check_out_date', Carbon::today())
            ->where('status', 'confirmed')
            ->count();

        // Occupancy rate
        $totalActiveRooms = Room::whereIn('status', ['available', 'occupied'])->count();
        $occupancyRate = $totalActiveRooms > 0 ? round(($occupiedRooms / $totalActiveRooms) * 100, 1) : 0;

        return view('dashboard.index', compact(
            // Basic counts
            'totalRooms', 'totalGuests', 'totalReservations', 'totalPayments',
            // Room status
            'availableRooms', 'occupiedRooms', 'maintenanceRooms',
            // Reservation status
            'pendingReservations', 'confirmedReservations', 'completedReservations', 'cancelledReservations',
            // Financial
            'totalRevenue', 'totalReservationValue', 'outstandingBalance', 'averagePayment',
            // Monthly
            'monthlyRevenue', 'monthlyReservations',
            // Activity
            'recentReservations', 'recentPayments', 'upcomingCheckins',
            // Today
            'todayCheckins', 'todayCheckouts', 'occupancyRate'
        ));
    }

    public function api()
    {
        // Basic counts
        $totalRooms = Room::count();
        $totalGuests = Guest::count();
        $totalReservations = Reservation::count();

        // Room status breakdown
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();

        // Financial statistics
        $totalRevenue = Payment::sum('amount_paid');

        // Today's activity
        $todayCheckins = Reservation::whereDate('check_in_date', Carbon::today())
            ->where('status', 'confirmed')
            ->count();
            
        $todayCheckouts = Reservation::whereDate('check_out_date', Carbon::today())
            ->where('status', 'confirmed')
            ->count();

        // Occupancy rate
        $totalActiveRooms = Room::whereIn('status', ['available', 'occupied'])->count();
        $occupancyRate = $totalActiveRooms > 0 ? round(($occupiedRooms / $totalActiveRooms) * 100, 1) : 0;

        // Recent activity
        $recentReservations = Reservation::with(['guest', 'room'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($reservation) {
                return [
                    'id' => $reservation->id,
                    'guest_name' => $reservation->guest->full_name,
                    'room_number' => $reservation->room->room_number,
                    'status' => $reservation->status,
                    'created_at' => $reservation->created_at
                ];
            });

        $recentPayments = Payment::with(['reservation.guest', 'reservation.room'])
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get()
            ->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'guest_name' => $payment->reservation->guest->full_name,
                    'room_number' => $payment->reservation->room->room_number,
                    'amount' => $payment->amount_paid,
                    'method' => $payment->payment_method,
                    'payment_date' => $payment->payment_date
                ];
            });

        return response()->json([
            'stats' => [
                'totalRooms' => $totalRooms,
                'totalGuests' => $totalGuests,
                'totalReservations' => $totalReservations,
                'totalRevenue' => $totalRevenue,
                'availableRooms' => $availableRooms,
                'occupiedRooms' => $occupiedRooms,
                'occupancyRate' => $occupancyRate,
                'todayCheckins' => $todayCheckins,
                'todayCheckouts' => $todayCheckouts,
            ],
            'recentReservations' => $recentReservations,
            'recentPayments' => $recentPayments
        ]);
    }
}
