<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'price',
        'liked',
        'description',
        'category_id',
        'views_count',
        'sales_count',
        'stock',
    ];

    protected $appends = ['img_url'];

    protected $casts = [
        'price' => 'float',
        'liked' => 'boolean',
        'views_count' => 'integer',
        'sales_count' => 'integer',
        'stock' => 'integer',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function metaData()
    {
        return $this->hasMany(ProductMetaData::class);
    }

    public function getImgUrlAttribute()
    {
        $firstImage = $this->relationLoaded('images')
            ? $this->images->first()
            : $this->images()->first();

        if (!$firstImage) {
            return asset('images/no-image.png');
        }

        return $firstImage->image_url;
    }
}
