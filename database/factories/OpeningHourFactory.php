<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\OpeningHour;

class OpeningHourFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OpeningHour::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'day' => $this->faker->randomElement(["monday","tuesday","wednesday","thursday","friday","saturday","sunday"]),
            'open' => $this->faker->time(),
            'close' => $this->faker->time(),
        ];
    }
}
