<?php

namespace App\Dto\Order;

use App\Dto\Product\ProductData;
use App\Enum\OrderStatus;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

/**
 * @property app/Dto/Product/ProductData[] $produkts
 */

class OrderData extends Data
{
    public function __construct(
        public int|Optional $id,
        public string|Optional $name,
        public string|Optional $email,
        public string|Optional $phone,
        public string|Optional $address,
        public string|Optional $postNr,
        public OrderStatus $status,
        #[DataCollectionOf(ProductData::class)]
        public array|Optional $products
    ) {}
}

