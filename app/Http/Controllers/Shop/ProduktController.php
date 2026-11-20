<?php

namespace App\Http\Controllers\Shop;

use App\Actions\Produkts\GetProduktListAction;
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
        GetProduktListAction $action

    ): Response {
        $data = $action->execute($request);

        return Inertia::render('Shop/Produkt/Index', [
            ...$data,
        ]);
    }

     /**
     * Display the specified resource.
     */
    public function show(int $produktid)
    {

        $produk = Produkt::find($produktid);

        $dto = ProductData::from($produk,['produktID' => $produk->id]);

        return Inertia::render('Shop/Produkt/Detail', [
            'produkt' => $dto,
        ]);
    }
}
