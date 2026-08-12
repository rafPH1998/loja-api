<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryMetaData extends Model
{
    use HasFactory;

    protected $table = 'category_meta_data';

    protected $fillable = ['id', 'name', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function values()
    {
        return $this->hasMany(MetaDataValue::class);
    }
}
