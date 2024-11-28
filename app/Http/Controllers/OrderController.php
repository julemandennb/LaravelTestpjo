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
        $orderList = Order::all();
        $produktList = Produkt::all();
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


        // Create the order
        $order = new Order([
            'user_id' => auth()->user()->id,
            'total_price' => 0, // Will update later after calculating total price
        ]);

        // Save the order to get an ID
        $order->save();

        // Get the products list from the request
        $produkts = $request->input('Produkts'); // This should be an array of products

        // Initialize total price
        $totalPrice = 0;

        // Loop through each product in the list
        foreach ($produkts as $produkt) {
            // You may want to validate the structure of each product in the list
            $productId = $produkt['id'];  // Assuming the product has an 'id' field
            // Retrieve the product from the database
            $product = Produkt::find($productId);
            if ($product) {

                $quantity = 1;
                $existingProduct = $order->products()->where('produkt_id', $productId)->first();
                if ($existingProduct) {
                    // If it exists, increment the quantity
                    $quantity = $existingProduct->pivot->quantity + 1;
                
                    // Update the pivot table with the new quantity
                    $order->products()->updateExistingPivot($productId, ['quantity' => $quantity]);
                } else {
                    // If it doesn't exist, attach it with quantity = 1
                    $order->products()->attach($productId, ['quantity' => $quantity, 'price' => $product->prise]);
                }
                // Calculate the total price for this product (price * quantity)
                $totalPrice += $product->prise;
            }
        }

        // Update the order's total price
        $order->total_price = $totalPrice;

        // Save the updated order
        $order->save();

        // Return the created order as a response
        return response()->noContent(200);

        
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
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
        //
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
