<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Salle;
use App\Models\Table;

class TableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Table::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'salle_id' => Salle::factory(),
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'capacity' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
