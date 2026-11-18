<?php

namespace App\Http\Controllers\Shop;

use App\Actions\Produkts\GetProduktListAction;
use App\Actions\User\ShopUserCheckAction;
use App\Dto\Product\ProductData;
use App\Http\Controllers\Controller;
use App\Models\Produkt;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProduktController extends Controller
{
    public function index(
        Request $request,
        ShopUserCheckAction $userCheckAction,
        GetProduktListAction $action

    ): Response {
        $data = $action->execute($request);
        $userCheck = $userCheckAction->execute();

        return Inertia::render('Shop/Produkt/Index', [
            ...$data,
            ...$userCheck
        ]);
    }

     /**
     * Display the specified resource.
     */
    public function show(int $produktid, ShopUserCheckAction $userCheckAction,)
    {
        $userCheck = $userCheckAction->execute();

        $produk = Produkt::find($produktid);

        $dto = ProductData::from($produk,['produktID' => $produk->id]);

        return Inertia::render('Shop/Produkt/Detail', [
            ...$userCheck,
            'produkt' => $dto,
        ]);
    }
}
