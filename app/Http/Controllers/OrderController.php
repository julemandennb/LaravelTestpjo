<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Produkt;
use Inertia\Inertia;



class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderList = Order::all()->load("products");
        $produktList = Produkt::select('id', 'name', 'prise')->get();
        return Inertia::render('Order/index',["orderList" => $orderList,"produktList" => $produktList]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $order = new Order([
            'user_id' => auth()->user()->id,
            'total_price' => 0,
        ]);

       
        $order->save();

        $produkts = $request->input('Produkts');

       
        $totalPrice = 0;

      
        foreach ($produkts as $produkt) {
            
            $productId = $produkt['id']; 
            
            $product = Produkt::find($productId);
            if ($product) {

                $quantity = 1;
                $existingProduct = $order->products()->where('produkt_id', $productId)->first();
                if ($existingProduct) {
                  
                    $quantity = $existingProduct->pivot->quantity + 1;
                
                    
                    $order->products()->updateExistingPivot($productId, ['quantity' => $quantity]);
                } else {
                   
                    $order->products()->attach($productId, ['quantity' => $quantity, 'price' => $product->prise]);
                }
               
                $totalPrice += $product->prise;
            }
        }

       
        $order->total_price = $totalPrice;

       
        $order->save();

       
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
        $produktList = Produkt::select('id', 'name', 'prise')->get();

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

                    $order->products()->attach($productId, ['quantity' => 1, 'price' => $product->prise]);
                }
                $totalPrice += $product->prise;
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
