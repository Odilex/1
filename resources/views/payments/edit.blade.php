@extends('layouts.app')

@section('title', 'Edit Payment')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Payment</h1>
        <p class="mt-1 text-sm text-gray-600">Update payment details for reservation #{{ $payment->reservation->id }}.</p>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <form action="{{ route('payments.update', $payment) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="reservation_id" class="block text-sm font-medium text-gray-700">
                        Reservation <span class="text-red-500">*</span>
                    </label>
                    <select id="reservation_id" 
                            name="reservation_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('reservation_id') border-red-500 @enderror"
                            required>
                        <option value="">Select a reservation</option>
                        @foreach($reservations as $reservation)
                            <option value="{{ $reservation->id }}" 
                                    data-total="{{ $reservation->total_amount }}"
                                    data-paid="{{ $reservation->payments->sum('amount_paid') }}"
                                    data-guest="{{ $reservation->guest->full_name }}"
                                    data-room="{{ $reservation->room->room_number }}"
                                    {{ old('reservation_id', $payment->reservation_id) == $reservation->id ? 'selected' : '' }}>
                                #{{ $reservation->id }} - {{ $reservation->guest->full_name }} (Room {{ $reservation->room->room_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('reservation_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="amount_paid" class="block text-sm font-medium text-gray-700">
                        Amount Paid (RWF) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="amount_paid" 
                           name="amount_paid" 
                           value="{{ old('amount_paid', $payment->amount_paid) }}"
                           step="0.01"
                           min="0.01"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('amount_paid') border-red-500 @enderror"
                           required>
                    @error('amount_paid')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <select id="payment_method" 
                            name="payment_method" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('payment_method') border-red-500 @enderror"
                            required>
                        <option value="">Select payment method</option>
                        <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ old('payment_method', $payment->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                        <option value="mobile_money" {{ old('payment_method', $payment->payment_method) == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700">
                        Payment Date <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" 
                           id="payment_date" 
                           name="payment_date" 
                           value="{{ old('payment_date', $payment->payment_date->format('Y-m-d\TH:i')) }}"
                           max="{{ now()->format('Y-m-d\TH:i') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('payment_date') border-red-500 @enderror"
                           required>
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700">
                        Reference Number
                    </label>
                    <input type="text" 
                           id="reference_number" 
                           name="reference_number" 
                           value="{{ old('reference_number', $payment->reference_number) }}"
                           placeholder="e.g., REF123456"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('reference_number') border-red-500 @enderror">
                    @error('reference_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">
                        Notes
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              placeholder="Any additional notes about this payment..."
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('notes') border-red-500 @enderror">{{ old('notes', $payment->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Balance Information -->
            <div id="balanceInfo" class="mt-6 p-4 bg-gray-50 rounded-md">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Reservation Balance Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Guest:</span>
                        <span id="guestName" class="font-medium text-gray-900 ml-2">-</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Room:</span>
                        <span id="roomNumber" class="font-medium text-gray-900 ml-2">-</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Total Amount:</span>
                        <span id="totalAmount" class="font-medium text-gray-900 ml-2">RWF 0</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Other Payments:</span>
                        <span id="alreadyPaid" class="font-medium text-green-600 ml-2">RWF 0</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-500">Available Balance:</span>
                        <span id="remainingBalance" class="font-bold text-blue-600 ml-2">RWF 0</span>
                    </div>
                </div>
                <div id="balanceWarning" class="mt-3 text-sm text-red-600 hidden"></div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('payments.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Update Payment
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reservationSelect = document.getElementById('reservation_id');
    const amountPaid = document.getElementById('amount_paid');
    const balanceInfo = document.getElementById('balanceInfo');
    const guestName = document.getElementById('guestName');
    const roomNumber = document.getElementById('roomNumber');
    const totalAmount = document.getElementById('totalAmount');
    const alreadyPaid = document.getElementById('alreadyPaid');
    const remainingBalance = document.getElementById('remainingBalance');
    const balanceWarning = document.getElementById('balanceWarning');
    const currentPaymentId = {{ $payment->id }};

    function updateBalanceInfo() {
        const selectedOption = reservationSelect.options[reservationSelect.selectedIndex];
        
        if (reservationSelect.value) {
            const total = parseFloat(selectedOption.dataset.total) || 0;
            const paid = parseFloat(selectedOption.dataset.paid) || 0;
            const amount = parseFloat(amountPaid.value) || 0;
            const otherPayments = paid - (currentPaymentId === {{ $payment->reservation_id }} ? {{ $payment->amount_paid }} : 0);
            const available = total - otherPayments;
            
            guestName.textContent = selectedOption.dataset.guest || '-';
            roomNumber.textContent = selectedOption.dataset.room || '-';
            totalAmount.textContent = 'RWF ' + total.toLocaleString();
            alreadyPaid.textContent = 'RWF ' + otherPayments.toLocaleString();
            remainingBalance.textContent = 'RWF ' + available.toLocaleString();
            
            // Check if amount exceeds available balance
            if (amount > available) {
                balanceWarning.textContent = 'Warning: Payment amount exceeds available balance by RWF ' + (amount - available).toLocaleString();
                balanceWarning.classList.remove('hidden');
            } else {
                balanceWarning.classList.add('hidden');
            }
        }
    }

    reservationSelect.addEventListener('change', updateBalanceInfo);
    amountPaid.addEventListener('input', updateBalanceInfo);
    
    // Initial update
    updateBalanceInfo();
});
</script>
@endpush
@endsection
