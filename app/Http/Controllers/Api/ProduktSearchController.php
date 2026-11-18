<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produkt;
use Illuminate\Http\Request;

class ProduktSearchController extends Controller
{
    public function __invoke(Request $request)
    {
       $search = $request->input('search');

        $products = Produkt::query()
            ->select([
                'id',
                'name',
                'price',
                'descript',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('price', 'like', "%{$search}%")
                        ->orWhere('descript', 'like', "%{$search}%");
                });
            })
            ->limit(5)
            ->get();

        return response()->json($products);

    }
}
