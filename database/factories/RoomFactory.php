<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roomTypes = ['Single', 'Double', 'Suite', 'Family'];
        $statuses = ['available', 'occupied', 'maintenance'];
        
        return [
            'room_number' => $this->faker->unique()->numberBetween(100, 399),
            'room_type' => $this->faker->randomElement($roomTypes),
            'price_per_night' => $this->faker->randomFloat(2, 25000, 150000), // RWF 25,000 - 150,000
            'capacity' => $this->faker->numberBetween(1, 4),
            'status' => $this->faker->randomElement($statuses),
            'description' => $this->faker->sentence(3),
        ];
    }
}
