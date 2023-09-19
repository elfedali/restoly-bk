<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\City;
use App\Models\Restaurant;
use App\Models\Street;
use App\Models\User;

class RestaurantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Restaurant::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'city_id' => City::factory(),
            'street_id' => Street::factory(),
            'address' => $this->faker->word,
            'approved_by' => User::factory()->create()->approved_by,
            'name' => $this->faker->name,
            'slug' => $this->faker->slug,
            'email' => $this->faker->safeEmail,
            'website' => $this->faker->word,
            'description' => $this->faker->text,
            'is_active' => $this->faker->boolean,
            'is_verified' => $this->faker->boolean,
            'is_featured' => $this->faker->boolean,
            'longitude' => $this->faker->longitude,
            'latitude' => $this->faker->latitude,
        ];
    }
}
