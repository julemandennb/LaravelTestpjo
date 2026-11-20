<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = Order::where('user_id', $user->id);

        $totalOrders = (clone $orders)->count();

        $processingOrders = (clone $orders)
            ->whereIn('status', ['new', 'processing', 'packed', 'shipped'])
            ->count();

        $completedOrders = (clone $orders)
            ->where('status', 'completed')
            ->count();

        $recentOrders = (clone $orders)
            ->latest()
            ->take(5)
            ->get([
                'uuid',
                'tracking_code',
                'total_price',
                'status',
                'created_at',
            ])
            ->map(fn ($order) => [
                'uuid' => $order->uuid,
                'tracking_code' => $order->tracking_code,
                'total_price' => $order->total_price,
                'status' => $order->status?->value,
                'created_at' => $order->created_at,
            ]);

        return Inertia::render('Shop/Customer/Dashboard', [
            'customer' => [
                'name' => $user->name,
                'email' => $user->email,
            ],

            'statistics' => [
                'total_orders' => $totalOrders,
                'processing_orders' => $processingOrders,
                'completed_orders' => $completedOrders,
            ],

            'recentOrders' => $recentOrders,
        ]);
    }
}
