<?php

namespace App\Http\Controllers\Shop;

use App\Actions\Checkout\CheckoutToOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\StoreCheckoutRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;


class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.cart.index');
        }

        return Inertia::render('Shop/Checkout/Index', [
            'cart' => array_values($cart),
        ]);
    }

    public function store(StoreCheckoutRequest $request,CheckoutToOrderAction $checkoutToOrderAction)
    {

        $checkoutData = $request->dto();
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return back()->withErrors([
                'checkout' => 'Your cart is empty.',
            ]);
        }


        $order = $checkoutToOrderAction->execute($checkoutData, $cart);

        if ($order === null) {
            return back()->withErrors([
                'checkout' => 'Order cannot be made right now. Please try again later or contact support.',
            ]);
        }



        session()->forget('cart');


        return redirect()->route('shop')->with(
            'success',
            'Your order has been successfully placed! Thank you for shopping with us. Your OrderNr is ' . $order->uuid
        );
    }
}
