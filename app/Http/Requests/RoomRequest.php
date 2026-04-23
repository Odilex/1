<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roomId = $this->route('room')?->id;

        return [
            'room_number' => 'required|string|max:10|unique:rooms,room_number,' . $roomId,
            'room_type' => 'required|string|in:single,double,suite,family',
            'capacity' => 'required|integer|min:1|max:10',
            'price_per_night' => 'required|numeric|min:0|max:1000000',
            'status' => 'required|string|in:available,occupied,maintenance',
            'description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'room_number.required' => 'Room number is required.',
            'room_number.unique' => 'This room number already exists.',
            'room_type.required' => 'Room type is required.',
            'room_type.in' => 'Room type must be one of: single, double, suite, family.',
            'capacity.required' => 'Capacity is required.',
            'capacity.min' => 'Capacity must be at least 1 guest.',
            'capacity.max' => 'Capacity cannot exceed 10 guests.',
            'price_per_night.required' => 'Price per night is required.',
            'price_per_night.min' => 'Price cannot be negative.',
            'price_per_night.max' => 'Price cannot exceed 1,000,000 RWF.',
            'status.required' => 'Room status is required.',
            'status.in' => 'Status must be one of: available, occupied, maintenance.',
        ];
    }
}
