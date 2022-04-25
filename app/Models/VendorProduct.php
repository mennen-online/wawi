<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorProduct extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'vendor_id',
        'product_id',
        'article_number',
        'price',
        'available'];

    protected $searchableFields = ['*', 'product.name', 'product.description'];

    protected $table = 'vendor_products';

    protected $casts = [
        'available' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }
}
