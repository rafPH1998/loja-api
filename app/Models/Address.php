<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['id', 'user_id', 'zipcode', 'street', 'number', 'city', 'state', 'country', 'complement'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
