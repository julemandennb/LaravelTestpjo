<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10)
            ->through(fn ($order) => [
                'uuid' => $order->uuid,
                'tracking_code' => $order->tracking_code,
                'total_price' => $order->total_price,
                'status' => $order->status?->value,
                'created_at' => $order->created_at,
            ]);

        return Inertia::render('Shop/Customer/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, Order $order)
    {
        abort_unless(
            $order->user_id === $request->user()->id,
            404
        );

        $order->load('products');

        return Inertia::render('Shop/Customer/Orders/Show', [
            'order' => [
                'uuid' => $order->uuid,
                'tracking_code' => $order->tracking_code,
                'status' => $order->status?->value,
                'total_price' => $order->total_price,
                'created_at' => $order->created_at,
                'name' => $order->name,
                'email' => $order->email,
                'phone' => $order->phone,
                'address' => $order->address,
                'postNr' => $order->postNr,

                'products' => $order->products->map(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->pivot->price ?? $product->price,
                    'quantity' => $product->pivot->quantity,
                ]),
            ],
        ]);
    }
}
