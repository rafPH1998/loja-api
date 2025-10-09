<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMetaData extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_meta_data_id',
        'meta_data_value_id',
        'product_id'
    ];
}
