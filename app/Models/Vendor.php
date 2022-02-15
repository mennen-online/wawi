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
    ];

    protected $searchableFields = ['*'];

    public function vendorProducts()
    {
        return $this->hasMany(VendorProduct::class);
    }
}
