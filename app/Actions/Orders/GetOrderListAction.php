<?php
namespace App\Actions\Orders;

use App\Models\Order;
use Illuminate\Http\Request;

class GetOrderListAction
{
    public function execute(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 10), 100);

        $search = $request->input('search');
        $sort = $request->input('sort', 'updated_at');
        $direction = $request->input('direction', 'desc');
        $status = $request->input('status', 'all');

        $allowedSorts = [
            'uuid',
            'name',
            'email',
            'phone',
            'total_price',
            'updated_at',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'updated_at';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $orderList = Order::query()
            ->select([
                'uuid',
                'total_price',
                'name',
                'email',
                'phone',
                'updated_at',
                'status',
            ])
            ->when($status !== 'all', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return [
            'orderList' => $orderList,
            'filters' => [
                'search' => $search,
                'sort' => $sort,
                'direction' => $direction,
                'per_page' => $perPage,
                'status' => $status,
            ],
        ];
    }
}
