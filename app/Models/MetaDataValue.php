<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MetaDataValue extends Model
{
    use HasFactory;
    
    protected $fillable = ['label', 'category_meta_data_id'];
}
