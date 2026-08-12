<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'url',
    ];

    protected $appends = ['image_url'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->url) {
            return asset('images/no-image.png');
        }

        if (
            str_starts_with($this->url, 'http://')
            || str_starts_with($this->url, 'https://')
            || str_starts_with($this->url, '/')
        ) {
            return $this->url;
        }

        return asset('storage/products/' . ltrim($this->url, '/'));
    }
}
