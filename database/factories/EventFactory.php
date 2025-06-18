<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 days', '+1 month');
        $end = (clone $start)->modify('+2 hours');

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'venue' => $this->faker->city,
            'start_date' => $start,
            'end_date' => $end,
            'capacity' => $this->faker->numberBetween(20, 200),
            'ticket_price' => $this->faker->randomFloat(2, 0, 100),
            'status' => 'published',
            'image_path' => null,
        ];
    }
}
