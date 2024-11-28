<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produkt extends Model
{
    /** @use HasFactory<\Database\Factories\ProduktFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'prise',
        'descript'
    ];

    // A product can belong to many orders (many-to-many)
    public function orders() :BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_produkts', 'produkt_id', 'order_id')
                    ->withPivot('quantity', 'price'); // You can store quantity and price on the pivot table
    }

}
