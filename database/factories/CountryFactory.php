<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Country;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $country =  $this->faker->country,
            'slug' => Str::slug($country),
            'is_active' => $this->faker->boolean,
            // for lang : en,fr,ar,es
            'lang' => $this->faker->randomElement(['en', 'fr', 'ar', 'es']),
            // for currency : MAD,USD,EUR
            'currency' => $this->faker->randomElement(['MAD', 'USD', 'EUR']),
            'currency_symbol' => $this->faker->randomElement(['DH', '$', '€']),

        ];
    }
}
