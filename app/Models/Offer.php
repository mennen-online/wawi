<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use App\Services\Lexoffice\Endpoints\Contacts;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ['name', 'contact_id', 'resource_id', 'user_id'];

    protected $searchableFields = ['*'];

    protected $appends = [
        'contact'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendorProducts()
    {
        return $this->belongsToMany(VendorProduct::class)->withPivot('quantity');
    }

    public function contact(): Attribute {
        return new Attribute(
            get: fn() => (new Contacts())->show($this->contact_id)
        );
    }
}
