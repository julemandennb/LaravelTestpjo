<?php
namespace App\Actions\Checkout;

use App\Actions\Order\CreateOrderAction;
use App\Dto\Cart\CartData;
use App\Dto\Checkout\CheckoutData;
use App\Dto\Order\OrderData;
use App\Dto\Product\ProductData;
use App\Enum\OrderStatus;
use App\Models\Order;

class CheckoutToOrderAction
{

     public function __construct(
        private CreateOrderAction $createOrderAction
    ) {}

    /**
     * @param array<CartData> $cartDatas
     */
    public function execute(CheckoutData $checkoutData,  array $cartDatas): Order
    {

        $productDatas = array_map(
            fn ($value) => ProductData::from([
                'produktID' => $value->produktID,
                'quantity' => $value->quantity,
            ]),
            $cartDatas
        );

        $orderData = OrderData::from([
            'name' => $checkoutData->name,
            'email' => $checkoutData->email,
            'phone' => $checkoutData->phone,
            'address' => $checkoutData->address,
            'postNr' => $checkoutData->postal_code,
            'status' => OrderStatus::NEW,
            'products' => $productDatas,

        ]);

        $userid = auth()->id() ?? 0;

       return $this->createOrderAction->execute($userid,$orderData);

    }
}
