<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->realText(200, 2),
            'start_time' => $this->faker->dateTimeBetween(Carbon::now(), Carbon::now()->addMonth()),
            'end_time' => $this->faker->dateTimeBetween(Carbon::now()->addMonth(3), Carbon::now()->addMonth(4)),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
