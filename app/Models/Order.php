<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', // foreign key to User
        'total_price', // You may want to store the total price of the order
    ];

    // A single order belongs to a single user (one-to-many)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // user_id is the foreign key
    }

    // An order can have many products (many-to-many)
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Produkt::class, 'order_produkts', 'order_id', 'produkt_id')
                    ->withPivot('quantity', 'price'); // You can also store the quantity and price in pivot table
    }
}
