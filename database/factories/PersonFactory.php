<?php

namespace Database\Factories;

use Faker\Provider\vi_VN\Person;
use App\Models\Person as PersonAlias;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('vi_VN');
        $faker->addProvider(new Person($faker));
        $phoneNumber = $faker->phoneNumber();
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        $formattedPhoneNumber = '0' . substr($phoneNumber, 1, 3) . substr($phoneNumber, 4, 3) . substr($phoneNumber, 7, 3);
        /*
        $storagePath = 'public\avatars';
        $fullStoragePath = Storage::path($storagePath);
        $image = $faker->image($fullStoragePath, $width = 640, $height = 480);
        $fileName = basename($image); */
        return [
            'name' => $faker->name(),
            'email' => $faker->unique()->email(),
            'birthday' => $this->faker->dateTimeBetween($startDate = '-35 years', $endDate = '-16 years'),
            'phone_number' => $formattedPhoneNumber,
            'image_link' => $faker->imageUrl($width = 640, $height = 480),
        ];
    }
}
