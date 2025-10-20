<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMetaData extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_meta_data_id',
        'meta_data_value_id',
        'product_id'
    ];

    public function metaValue()
    {
        return $this->belongsTo(MetaDataValue::class, 'meta_data_value_id');
    }

    public function metaData()
    {
        return $this->belongsTo(CategoryMetaData::class, 'category_meta_data_id');
    }
}
