<?php

namespace App\Http\Controllers\Shop;

use App\Actions\User\ShopUserCheckAction;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request,ShopUserCheckAction $userCheckAction)
    {

        $userCheck = $userCheckAction->execute();


        return Inertia::render('Welcome',  $userCheck);

    }
}
