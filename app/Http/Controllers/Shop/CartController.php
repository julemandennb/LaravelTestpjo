<?php

namespace App\Http\Controllers\Shop;

use App\Actions\User\ShopUserCheckAction;
use App\Dto\Cart\CartData;
use App\Http\Controllers\Controller;
use App\Models\Produkt;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produktID' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $produkt = Produkt::findOrFail($validated['produktID']);

        $cart = session()->get('cart', []);

        $produktID = $produkt->id;

        if (isset($cart[$produktID])) {
            $cart[$produktID]->quantity += $validated['quantity'];
        } else {
            $cartData = CartData::from([
                'produktID' => $produkt->id,
                'name' => $produkt->name,
                'price' => $produkt->price,
                'quantity' => $validated['quantity'],
            ]);
            $cart[$produktID] = $cartData;
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Product added to cart!');
    }

    public function index(ShopUserCheckAction $userCheckAction)
    {
        $userCheck = $userCheckAction->execute();

        $cart = session()->get('cart', []);
        return inertia('Shop/Cart/Index', [
            ... $userCheck,
            'cart' => array_values($cart),
        ]);
    }

    public function remove(int $id)
    {
        $cart = session()->get('cart', []);

        unset($cart[$id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Product removed from cart!');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Cart cleared!');
    }
}
