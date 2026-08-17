<?php

namespace app\Dto\Product;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ProductData extends Data
{
    public function __construct(
        public string|Optional $name,
        public int|Optional  $price,
        public string|Optional $descript
    ) {}
}

