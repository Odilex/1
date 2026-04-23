@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Serene Heights Hotel Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">Welcome to your hotel management dashboard. Here's an overview of your hotel's current status.</p>
    </div>

    <!-- Key Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Rooms -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Rooms</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalRooms }}</p>
                    <div class="flex space-x-2 mt-1">
                        <span class="text-xs text-green-600">{{ $availableRooms }} available</span>
                        <span class="text-xs text-red-600">{{ $occupiedRooms }} occupied</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Guests -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Guests</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalGuests }}</p>
                    <p class="text-xs text-gray-500 mt-1">Registered guests</p>
                </div>
            </div>
        </div>

        <!-- Total Reservations -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Reservations</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalReservations }}</p>
                    <div class="flex space-x-2 mt-1">
                        <span class="text-xs text-green-600">{{ $confirmedReservations }} confirmed</span>
                        <span class="text-xs text-yellow-600">{{ $pendingReservations }} pending</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">RWF {{ number_format($totalRevenue, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">RWF {{ number_format($monthlyRevenue, 0) }} this month</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Activity & Occupancy -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Today's Activity -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Today's Activity</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Check-ins</span>
                    <span class="text-lg font-semibold text-green-600">{{ $todayCheckins }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Check-outs</span>
                    <span class="text-lg font-semibold text-blue-600">{{ $todayCheckouts }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Monthly Reservations</span>
                    <span class="text-lg font-semibold text-purple-600">{{ $monthlyReservations }}</span>
                </div>
            </div>
        </div>

        <!-- Occupancy Rate -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Occupancy Rate</h3>
            <div class="flex items-center justify-center">
                <div class="relative">
                    <svg class="w-24 h-24">
                        <circle cx="50" cy="50" r="40" stroke="#e5e7eb" stroke-width="8" fill="none"></circle>
                        <circle cx="50" cy="50" r="40" stroke="#3b82f6" stroke-width="8" fill="none"
                                stroke-dasharray="{{ 2 * 3.14159 * 40 * $occupancyRate / 100 }} {{ 2 * 3.14159 * 40 }}"
                                stroke-dashoffset="0"
                                transform="rotate(-90 50 50)"></circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-2xl font-bold text-gray-900">{{ $occupancyRate }}%</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">{{ $occupiedRooms }} of {{ $availableRooms + $occupiedRooms }} rooms occupied</p>
            </div>
        </div>

        <!-- Room Status -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Room Status</h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-500">Available</span>
                        <span class="text-sm font-medium">{{ $availableRooms }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalRooms > 0 ? ($availableRooms / $totalRooms) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-500">Occupied</span>
                        <span class="text-sm font-medium">{{ $occupiedRooms }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-600 h-2 rounded-full" style="width: {{ $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-500">Maintenance</span>
                        <span class="text-sm font-medium">{{ $maintenanceRooms }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $totalRooms > 0 ? ($maintenanceRooms / $totalRooms) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Total Revenue</h4>
            <p class="text-xl font-bold text-green-600">RWF {{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Outstanding Balance</h4>
            <p class="text-xl font-bold text-red-600">RWF {{ number_format($outstandingBalance, 2) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Total Reservation Value</h4>
            <p class="text-xl font-bold text-gray-900">RWF {{ number_format($totalReservationValue, 2) }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h4 class="text-sm font-medium text-gray-500 mb-2">Average Payment</h4>
            <p class="text-xl font-bold text-blue-600">RWF {{ number_format($averagePayment, 2) }}</p>
        </div>
    </div>

    <!-- Recent Activity Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Reservations -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Reservations</h3>
            </div>
            <div class="overflow-hidden">
                @forelse($recentReservations as $reservation)
                    <div class="px-4 py-4 sm:px-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $reservation->guest->full_name }}</p>
                                <p class="text-sm text-gray-500">Room {{ $reservation->room->room_number }}</p>
                                <p class="text-xs text-gray-400">{{ $reservation->created_at->format('M d, H:i') }}</p>
                            </div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($reservation->status == 'confirmed') bg-green-100 text-green-800
                                @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($reservation->status == 'completed') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        No recent reservations
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Payments</h3>
            </div>
            <div class="overflow-hidden">
                @forelse($recentPayments as $payment)
                    <div class="px-4 py-4 sm:px-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->reservation->guest->full_name }}</p>
                                <p class="text-sm text-gray-500">Room {{ $payment->reservation->room->room_number }}</p>
                                <p class="text-xs text-gray-400">{{ $payment->payment_date->format('M d, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600">RWF {{ number_format($payment->amount_paid, 0) }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($payment->payment_method) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        No recent payments
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Check-ins -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Upcoming Check-ins</h3>
                <p class="text-xs text-gray-500">Next 7 days</p>
            </div>
            <div class="overflow-hidden">
                @forelse($upcomingCheckins as $checkin)
                    <div class="px-4 py-4 sm:px-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $checkin->guest->full_name }}</p>
                                <p class="text-sm text-gray-500">Room {{ $checkin->room->room_number }}</p>
                                <p class="text-xs text-gray-400">{{ $checkin->check_in_date->format('M d') }}</p>
                            </div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $checkin->num_nights }} night{{ $checkin->num_nights > 1 ? 's' : '' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        No upcoming check-ins
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
