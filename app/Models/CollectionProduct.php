<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'collectiontitle',
        'collectiondescription',
        'collectionimage1',
        'collectionimage2',
        'page',
        'page_reference',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'collection_product_product');
    }
}
