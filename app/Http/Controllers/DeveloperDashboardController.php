<?php

namespace App\Http\Controllers;

use App\Enum\OrderStatus;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DeveloperDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $totalUsers = User::All()->count();
        $Get5LastUser = User::orderBy('last_login', 'desc')
        ->select(['name','email','last_login'])
        ->take(5)
        ->get();

        $totalCharts = ChatMessage::All()->count();
        $totalUnreadCharts = ChatMessage::All()->count();


        $totalOrder = Order::All()->count();
        $today = Carbon::now();
        $totalOrderToday = Order::whereDate('created_at',$today)->count();
        $totalCompletedOrder = Order::where('status',OrderStatus::COMPLETED)->count();




        dd($Get5LastUser);
    }
}
