<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoursePerson>
 */
class CoursePersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'person_id' => Person::query()->inRandomOrder()->value('id'),
            'course_id' => Course::query()->inRandomOrder()->value('id'),
        ];
    }
}
