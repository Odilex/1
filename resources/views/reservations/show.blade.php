@extends('layouts.app')

@section('title', 'Reservation Details')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Reservation Details</h1>
        <p class="mt-1 text-sm text-gray-600">View detailed information about this reservation.</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Reservation #{{ $reservation->id }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Reservation details and payment information
            </p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Guest</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $reservation->guest->full_name }}<br>
                        <span class="text-gray-500">{{ $reservation->guest->email }} | {{ $reservation->guest->phone }}</span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Room</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $reservation->room->room_number }} - {{ $reservation->room->room_type }}<br>
                        <span class="text-gray-500">Capacity: {{ $reservation->room->capacity }} guests | {{ $reservation->room->status }}</span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Check-in Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $reservation->check_in_date->format('l, F j, Y') }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Check-out Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $reservation->check_out_date->format('l, F j, Y') }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Number of Nights</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $reservation->num_nights }} night{{ $reservation->num_nights > 1 ? 's' : '' }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Price per Night</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        RWF {{ number_format($reservation->room->price_per_night, 2) }}
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                    <dd class="mt-1 text-sm text-lg font-bold text-gray-900 sm:mt-0 sm:col-span-2">
                        RWF {{ number_format($reservation->total_amount, 2) }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($reservation->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($reservation->status == 'completed') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </dd>
                </div>
                @if($reservation->special_requests)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Special Requests</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $reservation->special_requests }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <div class="mt-6 flex space-x-3">
        <a href="{{ route('reservations.edit', $reservation) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Edit Reservation
        </a>
        <a href="{{ route('reservations.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
            Back to Reservations
        </a>
    </div>

    <!-- Payment History -->
    <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Payment History</h2>
            <a href="{{ route('payments.create', ['reservation_id' => $reservation->id]) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                Add Payment
            </a>
        </div>
        
        @if($reservation->payments->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount Paid
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Method
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reference
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reservation->payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    RWF {{ number_format($payment->amount_paid, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ ucfirst($payment->payment_method) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->payment_date->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->reference_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('payments.show', $payment) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Payment Summary -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-sm text-gray-500">Total Paid:</span>
                            <span class="text-lg font-bold text-green-600 ml-2">
                                RWF {{ number_format($reservation->payments->sum('amount_paid'), 2) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Remaining Balance:</span>
                            <span class="text-lg font-bold {{ $reservation->total_amount - $reservation->payments->sum('amount_paid') > 0 ? 'text-red-600' : 'text-green-600' }} ml-2">
                                RWF {{ number_format($reservation->total_amount - $reservation->payments->sum('amount_paid'), 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 text-center text-gray-500">
                    No payments recorded for this reservation yet.
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
