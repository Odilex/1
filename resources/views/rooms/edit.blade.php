@extends('layouts.app')

@section('title', 'Edit Room')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Room</h1>
        <p class="mt-1 text-sm text-gray-600">Update the details for room {{ $room->room_number }}.</p>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <form action="{{ route('rooms.update', $room) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="room_number" class="block text-sm font-medium text-gray-700">
                        Room Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="room_number" 
                           name="room_number" 
                           value="{{ old('room_number', $room->room_number) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('room_number') border-red-500 @enderror"
                           required>
                    @error('room_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="room_type" class="block text-sm font-medium text-gray-700">
                        Room Type <span class="text-red-500">*</span>
                    </label>
                    <select id="room_type" 
                            name="room_type" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('room_type') border-red-500 @enderror"
                            required>
                        <option value="">Select a room type</option>
                        <option value="Single" {{ old('room_type', $room->room_type) == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Double" {{ old('room_type', $room->room_type) == 'Double' ? 'selected' : '' }}>Double</option>
                        <option value="Suite" {{ old('room_type', $room->room_type) == 'Suite' ? 'selected' : '' }}>Suite</option>
                        <option value="Family" {{ old('room_type', $room->room_type) == 'Family' ? 'selected' : '' }}>Family</option>
                    </select>
                    @error('room_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price_per_night" class="block text-sm font-medium text-gray-700">
                        Price Per Night (RWF) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="price_per_night" 
                           name="price_per_night" 
                           value="{{ old('price_per_night', $room->price_per_night) }}"
                           step="0.01"
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('price_per_night') border-red-500 @enderror"
                           required>
                    @error('price_per_night')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700">
                        Capacity (Guests) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="capacity" 
                           name="capacity" 
                           value="{{ old('capacity', $room->capacity) }}"
                           min="1"
                           max="10"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('capacity') border-red-500 @enderror"
                           required>
                    @error('capacity')
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
                        <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('description') border-red-500 @enderror">{{ old('description', $room->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('rooms.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Update Room
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
