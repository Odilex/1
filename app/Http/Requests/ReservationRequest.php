<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
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
        $reservationId = $this->route('reservation')?->id;

        return [
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'status' => 'required|string|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
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
            'guest_id.required' => 'Guest selection is required.',
            'guest_id.exists' => 'Selected guest does not exist.',
            'room_id.required' => 'Room selection is required.',
            'room_id.exists' => 'Selected room does not exist.',
            'check_in_date.required' => 'Check-in date is required.',
            'check_in_date.after_or_equal' => 'Check-in date cannot be in the past.',
            'check_out_date.required' => 'Check-out date is required.',
            'check_out_date.after' => 'Check-out date must be after check-in date.',
            'status.required' => 'Reservation status is required.',
            'status.in' => 'Status must be one of: pending, confirmed, completed, cancelled.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateRoomAvailability($validator);
        });
    }

    /**
     * Validate room availability for the selected dates.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected function validateRoomAvailability($validator)
    {
        $roomId = $this->input('room_id');
        $checkIn = $this->input('check_in_date');
        $checkOut = $this->input('check_out_date');
        $reservationId = $this->route('reservation')?->id;

        if (!$roomId || !$checkIn || !$checkOut) {
            return;
        }

        // Check for overlapping reservations (excluding current reservation and cancelled ones)
        $conflictingReservation = \App\Models\Reservation::where('room_id', $roomId)
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    // New reservation starts during an existing reservation
                    $q->where('check_in_date', '<=', $checkIn)
                      ->where('check_out_date', '>', $checkIn);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    // New reservation ends during an existing reservation
                    $q->where('check_in_date', '<', $checkOut)
                      ->where('check_out_date', '>=', $checkOut);
                })->orWhere(function ($q) use ($checkIn, $checkOut) {
                    // New reservation completely contains an existing reservation
                    $q->where('check_in_date', '>=', $checkIn)
                      ->where('check_out_date', '<=', $checkOut);
                });
            })
            ->where('status', '!=', 'cancelled')
            ->when($reservationId, function ($query, $reservationId) {
                return $query->where('id', '!=', $reservationId);
            })
            ->first();

        if ($conflictingReservation) {
            $validator->errors()->add('room_id', 'This room is already booked for the selected dates. Please choose different dates or another room.');
        }
    }
}
