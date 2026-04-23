@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Payment Details</h1>
        <p class="mt-1 text-sm text-gray-600">View detailed information about this payment.</p>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Payment #{{ $payment->id }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Payment details and reservation information
            </p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Reservation</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        #{{ $payment->reservation->id }} - {{ $payment->reservation->room->room_number }} ({{ $payment->reservation->room->room_type }})<br>
                        <span class="text-gray-500">{{ $payment->reservation->guest->full_name }}</span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Guest</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $payment->reservation->guest->full_name }}<br>
                        <span class="text-gray-500">{{ $payment->reservation->guest->email }} | {{ $payment->reservation->guest->phone }}</span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Amount Paid</dt>
                    <dd class="mt-1 text-sm text-lg font-bold text-green-600 sm:mt-0 sm:col-span-2">
                        RWF {{ number_format($payment->amount_paid, 2) }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($payment->payment_method) }}
                        </span>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $payment->payment_date->format('l, F j, Y \a\t g:i A') }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Reference Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $payment->reference_number ?? 'N/A' }}
                    </dd>
                </div>
                @if($payment->notes)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $payment->notes }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Reservation Balance Summary -->
    <div class="mt-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Reservation Balance Summary</h2>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Total Reservation Amount</p>
                        <p class="text-xl font-bold text-gray-900">
                            RWF {{ number_format($payment->reservation->total_amount, 2) }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Total Paid</p>
                        <p class="text-xl font-bold text-green-600">
                            RWF {{ number_format($payment->reservation->payments->sum('amount_paid'), 2) }}
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">Remaining Balance</p>
                        <p class="text-xl font-bold {{ $payment->reservation->total_amount - $payment->reservation->payments->sum('amount_paid') > 0 ? 'text-red-600' : 'text-green-600' }}">
                            RWF {{ number_format($payment->reservation->total_amount - $payment->reservation->payments->sum('amount_paid'), 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History for this Reservation -->
    <div class="mt-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Payment History for Reservation #{{ $payment->reservation->id }}</h2>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Payment ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Method
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reference
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($payment->reservation->payments as $paymentRecord)
                        <tr class="{{ $paymentRecord->id === $payment->id ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $paymentRecord->id }}
                                @if($paymentRecord->id === $payment->id)
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Current
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                RWF {{ number_format($paymentRecord->amount_paid, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ ucfirst($paymentRecord->payment_method) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $paymentRecord->payment_date->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $paymentRecord->reference_number ?? 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex space-x-3">
        <a href="{{ route('payments.edit', $payment) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Edit Payment
        </a>
        <a href="{{ route('reservations.show', $payment->reservation) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            View Reservation
        </a>
        <a href="{{ route('payments.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
            Back to Payments
        </a>
    </div>
</div>
@endsection
