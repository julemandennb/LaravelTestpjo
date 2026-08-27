<?php

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrderAction;
use App\Actions\Order\UpdateOrderAction;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Produkt;
use App\Enum\OrderStatus;
use App\Models\OrderProdukt;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\LaravelData\Optional;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 10), 100);

        $search = $request->input('search');
        $sort = $request->input('sort', 'updated_at');
        $direction = $request->input('direction', 'desc');
        $status = $request->input('status','all');

        $allowedSorts = [
            'uuid',
            'name',
            'email',
            'phone',
            'total_price',
            'updated_at',
        ];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'updated_at';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
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
                'status'
            ])
            ->when($status, function($query, $status){
                if($status !="all")
                    $query->where('status',$status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Order/index', [
            'orderList' => $orderList,
            'statusList' => OrderStatus::list(),
            'filters' => [
                'search' => $search,
                'sort' => $sort,
                'direction' => $direction,
                'per_page' => $perPage,
                'status' => $status
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $produktList = Produkt::select('id', 'name', 'price')->get();

        return Inertia::render('Order/create',["produktList" => $produktList,"statusList" => OrderStatus::list()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request,CreateOrderAction $createOrder)
    {

        $createOrder->execute(
            userId: auth()->id(),
            orderData: $request->dto()
        );

        return response()->noContent(200);


    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load([
            'products' => function ($query) {

             $query->select('order_produkts.id','quantity', 'name','order_produkts.price');

            },
            'user' => function ($query) {
                $query->select('id', 'name'); // Always include the `id` to maintain relationships
            },
        ]);
        $produktList = Produkt::select('id', 'name', 'price')->get();

        return Inertia::render('Order/show',["order" => $order,"produktList" => $produktList,"statusList" => OrderStatus::list()]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order, UpdateOrderAction $updateOrderAction)
    {

        $updateOrderAction->execute(
            orderData: $request->dto(),
            order:$order
        );

        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('order.index');
    }




}
