<?php

namespace App\Http\Controllers;

use App\Enum\OrderStatus;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;


class DeveloperDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $totalUsers = User::count();
        $get5LastUser = User::query()
            ->select(['name', 'email', 'last_login'])
            ->orderByDesc('last_login')
            ->limit(5)
            ->get();
        $activeUsers = User::query()
            ->select(['name', 'email'])
            ->mostActive(5)
            ->get();

        $totalCharts = ChatMessage::count();
        $totalUnreadCharts = ChatMessage::where('seen', 0)->count();

        $totalOrder = Order::count();
        $today = Carbon::today();
        $totalOrderToday = Order::whereBetween('created_at', [
            $today->copy()->startOfDay(),
            $today->copy()->endOfDay(),
        ])->count();
        $totalCompletedOrder = Order::where('status', OrderStatus::COMPLETED)->count();

        return Inertia::render('DeveloperDashboard/index', [
            'totalUsers' => $totalUsers,
            'get5LastUser' => $get5LastUser,
            'activeUsers' => $activeUsers,
            'totalCharts' => $totalCharts,
            'totalUnreadCharts' => $totalUnreadCharts,
            'totalOrder' => $totalOrder,
            'totalOrderToday' => $totalOrderToday,
            'totalCompletedOrder' => $totalCompletedOrder
        ]);


    }
}
