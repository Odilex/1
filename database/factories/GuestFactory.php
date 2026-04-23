<?php

namespace Database\Factories;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guest>
 */
class GuestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nationalities = ['Rwanda', 'Kenya', 'Uganda', 'Tanzania', 'Burundi', 'DRC', 'France', 'Belgium', 'USA', 'UK'];
        
        return [
            'full_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => '+250' . $this->faker->numberBetween(700000000, 799999999),
            'nationality' => $this->faker->randomElement($nationalities),
            'id_number' => $this->faker->unique()->numerify('##########'),
        ];
    }
}
