<?php

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrderAction;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Produkt;
use App\Enum\OrderStatus;
use Inertia\Inertia;
use Illuminate\Http\Request;


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
            'id',
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
                'id',
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
            'products',
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
    public function update(UpdateOrderRequest $request, Order $order)
    {

        $produkts = $request->input('Produkts');

        $newProduktIds = collect($produkts)->pluck('id')->toArray();

        $oldprodukts = $order->products;

        $toRemove = $oldprodukts->whereNotIn('id', $newProduktIds)->pluck('id');


        $order->products()->detach($toRemove);


        $totalPrice = 0;


        foreach ($produkts as $produkt) {

            $productId = $produkt['id'];


            $product = Produkt::find($productId);

            if ($product) {

                $existingProduct = $order->products()->where('produkt_id', $productId)->first();

                if ($existingProduct) {

                    $quantity = $existingProduct->pivot->quantity + 1;
                    $order->products()->updateExistingPivot($productId, ['quantity' => $quantity]);
                } else {

                    $order->products()->attach($productId, ['quantity' => 1, 'price' => $product->price]);
                }
                $totalPrice += $product->price;
            }
        }

        $order->total_price = $totalPrice;

        $order->save();

        return response()->noContent(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->noContent(200);
    }




}
