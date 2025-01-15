<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Specify the table name (optional if it's the default "orders")
    protected $table = 'orders';  // Only needed if the table name is different than the model name (plural of "Order")

    // Define which attributes can be mass-assigned
    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'basket',
    ];

    // Optionally disable timestamps if your table does not have 'created_at' and 'updated_at' columns
    public $timestamps = true;

    // If you are using JSON fields like "basket", you can cast them to an array
    protected $casts = [
        'basket' => 'array',
    ];
}
