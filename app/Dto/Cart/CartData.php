<?php

namespace App\Dto\Cart;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class CartData extends Data
{
    public function __construct(
        public int $produktID,
        public string $name,
        public int $price,
        public int $quantity,

    ) {}
}
