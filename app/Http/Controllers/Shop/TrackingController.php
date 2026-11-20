<?php

namespace App\Http\Controllers\Shop;

use App\Actions\User\ShopUserCheckAction;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enum\OrderStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;


class TrackingController extends Controller
{
    public function index(Request $request,ShopUserCheckAction $userCheckAction)
    {
        $userCheck = $userCheckAction->execute();
        $tracking = null;
        $error = null;

        $request->validate([
            'tracking_number' => ['nullable', 'string', 'max:100'],
        ]);

        if ($request->filled('tracking_number')) {
            $order = Order::with('products')
                ->where('tracking_code', strtoupper(trim($request->tracking_number)))
                ->first();

            if ($order) {

                $history = Activity::forSubject($order)->orderby("id","desc")->get()->map(function ($activity) {
                        $properties = $activity->attribute_changes;

                        $oldStatus = data_get($properties, 'old.status');
                        $newStatus = data_get($properties, 'attributes.status');

                        if ($newStatus === null) {
                            return null;
                        }

                        return [
                            'old_status' => $oldStatus,
                            'status' => $newStatus,
                            'created_at' => $activity->created_at,
                        ];
                    })
                    ->filter()
                    ->values();

                $tracking = [
                    'tracking_number' => $order->tracking_code,
                    'status' => $order->status?->value,
                    'updated_at' => $order->updated_at,
                    'items' => $order->products,
                    'history' => $history,
                ];
            } else {
                $error = 'We could not find an order with this tracking number.';
            }
        }

        return Inertia::render('Shop/Tracking/Index', [
            'tracking' => $tracking,
            'error' => $error,
            ...$userCheck
        ]);
    }
}
