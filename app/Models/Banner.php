<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['link', 'img'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!$this->img) {
            return asset('images/no-image.png');
        }

        if (
            str_starts_with($this->img, 'http://')
            || str_starts_with($this->img, 'https://')
            || str_starts_with($this->img, '/')
        ) {
            return $this->img;
        }

        return asset('storage/' . ltrim($this->img, '/'));
    }
}
