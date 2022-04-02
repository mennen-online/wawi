<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'resource_id',
        'company',
        'email',
        'phone',
        'salutation',
        'first_name',
        'last_name',
        'username',
        'password',
        'csv_url',
    ];

    protected $casts = [
        'username' => 'encrypted',
        'password' => 'encrypted'
    ];

    protected $searchableFields = ['*'];

    protected $hidden = ['password'];

    public function vendorProducts()
    {
        return $this->hasMany(VendorProduct::class);
    }

    public function products() {
        return $this->hasManyThrough(Product::class, VendorProduct::class, 'vendor_id', 'id', 'id');
    }
}
