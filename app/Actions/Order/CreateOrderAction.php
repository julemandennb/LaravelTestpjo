<?php
namespace App\Actions\Order;

use App\Dto\Order\OrderData;
use App\Models\Order;
use App\Models\Produkt;
use Illuminate\Support\Facades\DB;

class CreateOrderAction
{
    public function execute(int $userId, OrderData $orderData): Order
    {
        return DB::transaction(function () use ($userId, $orderData) {

            $produkts = $orderData->products;
            $quantities = collect($produkts)
                ->groupBy('id')
                ->map(fn ($items) => $items->count());

            $productModels = Produkt::query()
                ->whereIn('id', $quantities->keys())
                ->get()
                ->keyBy('id');

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
            $pivotData = [];

            foreach ($quantities as $productId => $quantity) {
                $product = $productModels->get($productId);

                if (! $product) {
                    continue;
                }

                $pivotData[$productId] = [
                    'quantity' => $quantity,
                    'price' => $product->price,
                ];

                $totalPrice += $product->price * $quantity;
            }

            $order->products()->attach($pivotData);

            $order->update([
                'total_price' => $totalPrice,
            ]);

            return $order;
        });
    }
}
