<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'url',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->url) {
            return asset('storage/products/' . $this->url);
        }
    
        return asset('images/no-image.png');
    }
    
}
