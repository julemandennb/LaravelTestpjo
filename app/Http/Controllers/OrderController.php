<?php

namespace App\Http\Controllers;

use App\Actions\Order\CreateOrderAction;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Produkt;
use App\Enum\OrderStatus;
use Inertia\Inertia;



class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderList = Order::all()->load("products");
        $produktList = Produkt::select('id', 'name', 'price')->get();
        return Inertia::render('Order/index',["orderList" => $orderList,"produktList" => $produktList]);
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

        return Inertia::render('Order/show',["order" => $order,"produktList" => $produktList]);
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
