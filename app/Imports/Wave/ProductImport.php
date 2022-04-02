<?php

namespace App\Imports\Wave;

use App\Models\Product;
use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel
{

    public function model(array $row) {
        $vendor = Vendor::where('company', 'LIKE', 'WAVE%')->first();

        $product = Product::firstOrCreate(
            [
                'name' => $row['Manufacturer'].' '.$row['Name'],
            ],
            [
                'description' => $row['Col1'].' '.$row['Col2'].' '.$row['Col3']

            ]);

        $vendor->vendorProducts()->updateOrCreate(
            [
                'product_id'     => $product->id,
                'article_number' => $row['ArtNo'],
            ],
            [
                'price'     => $row['Price (EUR)'],
                'available' => $row['Availability'] === 'Auf Lager'
            ]);
    }
}
