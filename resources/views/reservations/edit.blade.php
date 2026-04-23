@extends('layouts.app')

@section('title', 'Edit Reservation')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Reservation</h1>
        <p class="mt-1 text-sm text-gray-600">Update the reservation details for {{ $reservation->guest->full_name }}.</p>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="guest_id" class="block text-sm font-medium text-gray-700">
                        Guest <span class="text-red-500">*</span>
                    </label>
                    <select id="guest_id" 
                            name="guest_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('guest_id') border-red-500 @enderror"
                            required>
                        <option value="">Select a guest</option>
                        @foreach($guests as $guest)
                            <option value="{{ $guest->id }}" {{ old('guest_id', $reservation->guest_id) == $guest->id ? 'selected' : '' }}>
                                {{ $guest->full_name }} ({{ $guest->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('guest_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="room_id" class="block text-sm font-medium text-gray-700">
                        Room <span class="text-red-500">*</span>
                    </label>
                    <select id="room_id" 
                            name="room_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('room_id') border-red-500 @enderror"
                            required>
                        <option value="">Select a room</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" 
                                    data-price="{{ $room->price_per_night }}"
                                    {{ old('room_id', $reservation->room_id) == $room->id ? 'selected' : '' }}>
                                {{ $room->room_number }} - {{ $room->room_type }} (RWF {{ number_format($room->price_per_night, 2) }}/night)
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="check_in_date" class="block text-sm font-medium text-gray-700">
                        Check-in Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="check_in_date" 
                           name="check_in_date" 
                           value="{{ old('check_in_date', $reservation->check_in_date->format('Y-m-d')) }}"
                           min="{{ today()->format('Y-m-d') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('check_in_date') border-red-500 @enderror"
                           required>
                    @error('check_in_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="check_out_date" class="block text-sm font-medium text-gray-700">
                        Check-out Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="check_out_date" 
                           name="check_out_date" 
                           value="{{ old('check_out_date', $reservation->check_out_date->format('Y-m-d')) }}"
                           min="{{ old('check_in_date', $reservation->check_in_date->format('Y-m-d')) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('check_out_date') border-red-500 @enderror"
                           required>
                    @error('check_out_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('status') border-red-500 @enderror"
                            required>
                        <option value="">Select status</option>
                        <option value="pending" {{ old('status', $reservation->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ old('status', $reservation->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ old('status', $reservation->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $reservation->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="special_requests" class="block text-sm font-medium text-gray-700">
                        Special Requests
                    </label>
                    <textarea id="special_requests" 
                              name="special_requests" 
                              rows="3"
                              placeholder="Any special requests or notes for this reservation..."
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('special_requests') border-red-500 @enderror">{{ old('special_requests', $reservation->special_requests) }}</textarea>
                    @error('special_requests')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Calculation Summary -->
            <div class="mt-6 p-4 bg-gray-50 rounded-md">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Reservation Summary</h3>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Number of Nights:</span>
                        <span id="num_nights" class="font-medium text-gray-900 ml-2">{{ $reservation->num_nights }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Price per Night:</span>
                        <span id="price_per_night" class="font-medium text-gray-900 ml-2">RWF {{ number_format($reservation->room->price_per_night, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Total Amount:</span>
                        <span id="total_amount" class="font-bold text-gray-900 ml-2">RWF {{ number_format($reservation->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('reservations.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Update Reservation
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_id');
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const numNights = document.getElementById('num_nights');
    const pricePerNight = document.getElementById('price_per_night');
    const totalAmount = document.getElementById('total_amount');

    function calculateTotal() {
        const selectedRoom = roomSelect.options[roomSelect.selectedIndex];
        const price = parseFloat(selectedRoom.dataset.price) || 0;
        
        if (checkInDate.value && checkOutDate.value) {
            const checkIn = new Date(checkInDate.value);
            const checkOut = new Date(checkOutDate.value);
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            
            if (nights > 0) {
                numNights.textContent = nights;
                pricePerNight.textContent = 'RWF ' + price.toLocaleString();
                totalAmount.textContent = 'RWF ' + (nights * price).toLocaleString();
            } else {
                numNights.textContent = '0';
                pricePerNight.textContent = 'RWF ' + price.toLocaleString();
                totalAmount.textContent = 'RWF 0';
            }
        } else {
            numNights.textContent = '0';
            pricePerNight.textContent = 'RWF ' + price.toLocaleString();
            totalAmount.textContent = 'RWF 0';
        }
    }

    roomSelect.addEventListener('change', calculateTotal);
    checkInDate.addEventListener('change', function() {
        checkOutDate.min = this.value;
        if (checkOutDate.value && checkOutDate.value <= this.value) {
            const tomorrow = new Date(this.value);
            tomorrow.setDate(tomorrow.getDate() + 1);
            checkOutDate.value = tomorrow.toISOString().split('T')[0];
        }
        calculateTotal();
    });
    checkOutDate.addEventListener('change', calculateTotal);
    
    // Initial calculation
    calculateTotal();
});
</script>
@endpush
@endsection
