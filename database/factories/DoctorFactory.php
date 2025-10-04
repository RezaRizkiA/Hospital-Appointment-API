<?php

namespace Database\Factories;

use App\Models\Hospital;
use App\Models\Specialist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
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
            'photo' => 'specialists/' . Str::uuid() . '.jpg',
            'about' => $this->faker->paragraph(),
            'yoe' => $this->faker->numberBetween(1, 10),
            'specialist_id' => Specialist::factory(),
            'hospital_id' => Hospital::factory(),
            'gender' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
        ];
    }
}
