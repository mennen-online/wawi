<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'resource_id' => $this->faker->text(255),
            'company' => $this->faker->text(255),
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'salutation' => $this->faker->text(255),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'username' => $this->faker->text(255),
            'password' => $this->faker->password,
            'csv_url' => $this->faker->text(255),
        ];
    }
}
