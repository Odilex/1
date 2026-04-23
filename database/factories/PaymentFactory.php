<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethods = ['cash', 'card', 'mobile_money', 'bank_transfer'];
        
        return [
            'reservation_id' => \App\Models\Reservation::factory(),
            'amount_paid' => $this->faker->randomFloat(2, 25000, 500000),
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'payment_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'reference_number' => $this->faker->optional(0.7)->numerify('REF########'),
            'notes' => $this->faker->optional(0.2)->sentence(2),
        ];
    }
}
