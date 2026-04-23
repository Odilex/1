<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        $checkIn = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $checkOut = (clone $checkIn)->modify('+' . $this->faker->numberBetween(1, 7) . ' days');
        $numNights = $checkOut->diff($checkIn)->days;
        $pricePerNight = $this->faker->randomFloat(2, 25000, 150000);
        
        return [
            'guest_id' => \App\Models\Guest::factory(),
            'room_id' => \App\Models\Room::factory(),
            'check_in_date' => $checkIn->format('Y-m-d'),
            'check_out_date' => $checkOut->format('Y-m-d'),
            'num_nights' => $numNights,
            'total_amount' => $numNights * $pricePerNight,
            'status' => $this->faker->randomElement($statuses),
            'special_requests' => $this->faker->optional(0.3)->sentence(3),
        ];
    }
}
