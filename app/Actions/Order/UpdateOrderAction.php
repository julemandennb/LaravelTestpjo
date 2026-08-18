<?php
namespace App\Actions\Order;

use App\Dto\Order\OrderData;
use App\Models\Order;
use App\Models\OrderProdukt;
use App\Models\Produkt;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Optional;

class UpdateOrderAction
{
    public function execute(OrderData $orderData,Order $order): Order
    {
        return DB::transaction(function () use ($orderData,$order) {

            $products = collect($orderData->products);

            // Pivot IDs that still exist in the request
            $newPivotIds = $products
                ->filter(fn ($product) => !($product->id instanceof Optional))
                ->pluck('id')
                ->values();

            // Remove products that were deleted from the request
            $order->removeProductsExceptPivotIds($newPivotIds);

            foreach ($products as $produkt) {


                $quantity = $produkt->quantity;

                // Existing pivot row
                if (!($produkt->id instanceof Optional)) {

                    $orderProdukt = OrderProdukt::where('id', $produkt->id)
                        ->where('order_id', $order->id)
                        ->first();

                    if ($orderProdukt) {
                        $orderProdukt->update([
                            'quantity' => $quantity,
                        ]);
                    }

                } else {

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
                        'quantity' => $quantity,
                        'price' => $product->price,
                    ]);
                }

            }

            $totalPrice = OrderProdukt::where('order_id', $order->id)
            ->get()
            ->sum(fn ($item) => $item->price * $item->quantity);

            $order->update([
                'total_price' => $totalPrice,
                'name' => $orderData->name,
                'email' => $orderData->email,
                'phone' => $orderData->phone,
                'address'  => $orderData->address,
                'postNr' => $orderData->postNr,
                'status' => $orderData->status
            ]);

            return $order;
        });
    }
}
