<?php

namespace App\Http\Controllers\Shop;

use App\Actions\Produkts\GetProduktListAction;
use App\Actions\User\ShopUserCheckAction;
use App\Http\Controllers\Controller;
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
}
