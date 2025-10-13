<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'label',
        'price',
        'description',
        'category_id',
        'views_count',
        'sales_count',
    ];

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
}
