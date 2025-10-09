<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total',
        'shipping_cost',
        'shipping_days',
        'shipping_zipcode',
        'shipping_street',
        'shipping_number',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_complement',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
