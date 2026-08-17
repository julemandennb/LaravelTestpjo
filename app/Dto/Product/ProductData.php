<?php

namespace App\Dto\Product;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class ProductData extends Data
{
    public function __construct(
        public int|Optional $id,
        public string|Optional $name,
        public int|Optional  $price,
        public string|Optional $descript
    ) {}

    public static function prepareForPipeline(array $properties): array
    {
        if (isset($properties['price'])) {
            $properties['price'] = (int) round((float) $properties['price'] * 100);
        }

        return $properties;
    }
}

