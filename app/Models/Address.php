<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;
    
    protected $fillable = ['id', 'user_id', 'zipcode', 'street', 'number', 'city', 'state', 'country', 'complement'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
