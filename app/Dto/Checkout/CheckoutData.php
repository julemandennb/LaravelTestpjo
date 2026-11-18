<?php

namespace App\Dto\Checkout;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

class CheckoutData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $address,
        public string $postal_code,
        public string $city,
    ) {}
}

