<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;
    
    protected $fillable = ['link', 'img'];

   /*  public function user()
    {
        return $this->belongsTo(User::class);
    } */

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->img) {
            return asset('storage/' . $this->img);
        }
    
        return asset('images/no-image.png');
    }
    
    
}
