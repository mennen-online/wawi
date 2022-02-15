<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Offer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'resource_id' => $this->faker->text(255),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
