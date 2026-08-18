<?php
namespace App\Actions\Order;

use App\Dto\Order\OrderData;
use App\Models\Order;
use App\Models\OrderProdukt;
use App\Models\Produkt;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Optional;

class CreateOrderAction
{
    public function execute(int $userId, OrderData $orderData): Order
    {
        return DB::transaction(function () use ($userId, $orderData) {


            $order = Order::create([
                'user_id' => $userId,
                'total_price' => 0,
                'name' => $orderData->name,
                'email' => $orderData->email,
                'phone' => $orderData->phone,
                'address'  => $orderData->address,
                'postNr' => $orderData->postNr,
                'status' => $orderData->status
            ]);

            $totalPrice = 0;


            foreach ($orderData->products as $produkt) {

                if ($produkt->produktID instanceof Optional) {
                    continue;
                }

                $product = Produkt::find($produkt->produktID);

                if (!$product) {
                    continue;
                }

                // New product
                OrderProdukt::create([
                    'order_id' => $order->id,
                    'produkt_id' => $product->id,
                    'quantity' => $produkt->quantity,
                    'price' => $product->price,
                ]);

                $totalPrice += $product->price * $produkt->quantity;
            }


            $order->update([
                'total_price' => $totalPrice,
            ]);

            return $order;
        });
    }
}
