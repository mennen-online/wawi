<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\VendorProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VendorProduct::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'price' => $this->faker->randomFloat(2, 0, 9999),
            'available' => $this->faker->boolean,
            'vendor_id' => \App\Models\Vendor::factory(),
            'product_id' => \App\Models\Product::factory(),
        ];
    }
}
