<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    function customer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
