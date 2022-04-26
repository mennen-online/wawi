<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'image_url'];

    protected $searchableFields = ['name'];

    public function scopeSearch($query, $search) {
        $query->where(function($query) use($search){
            foreach($this->getSearchableFields() as $field) {
                foreach(explode(' ', $search) as $searchTerm) {
                    $query->orWhere($field, 'LIKE', '%'.$searchTerm.'%');
                }
            }
        });
    }

    public function vendorProducts()
    {
        return $this->hasMany(VendorProduct::class);
    }
}
