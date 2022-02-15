<?php

namespace Database\Seeders;

use App\Models\VendorProduct;
use Illuminate\Database\Seeder;

class VendorProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VendorProduct::factory()
            ->count(5)
            ->create();
    }
}
