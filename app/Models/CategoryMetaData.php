<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryMetaData extends Model
{
    protected $fillable = ['id', 'name', 'category_id'];
}
