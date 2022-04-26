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

    public function contactName(): Attribute {
        return new Attribute(
            get: function() {
                if($this->isCompany()) {
                    return $this->companyName;
                }
                return $this->personName;
            }
        );
    }

    public function isCompany(): bool {
        return property_exists($this->contact, 'company');
    }

    public function isPerson(): bool {
        return property_exists($this->contact, 'person');
    }

    public function companyName(): Attribute {
        return new Attribute(
            get: fn() => $this->contact->company->name
        );
    }

    public function personName(): Attribute {
        return new Attribute(
            get: fn() => $this->contact->person->firstName . ' ' . $this->contact->person->lastName
        );
    }

    public function contact(): Attribute {
        return new Attribute(
            get: fn() => (new Contacts())->show($this->contact_id)
        );
    }
}
