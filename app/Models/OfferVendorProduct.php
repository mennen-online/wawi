<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferVendorProduct extends Model
{
    use HasFactory;

    protected $table = 'offer_vendor_product';

    protected $fillable = [
        'vendor_product_id',
        'offer_id',
        'quantity'
    ];
}
