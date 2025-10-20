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
        'description',
        'category_id',
        'views_count',
        'sales_count',
    ];

    protected $appends = ['img_url'];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function metaData()
    {
        return $this->hasMany(ProductMetaData::class);
    }

    public function getImgUrlAttribute()
    {
        $firstImage = $this->images()->first();
        return $firstImage ? $firstImage->image_url : asset('images/no-image.png');
    }
}
