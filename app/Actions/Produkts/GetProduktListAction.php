<?php
namespace App\Actions\Produkts;

use App\Dto\Product\ProductData;
use App\Models\Produkt;
use Illuminate\Http\Request;

class GetProduktListAction
{
    public function execute(Request $request)
    {
        $perPage = min(
            max((int) $request->input('per_page', 10), 1),
            100
        );

        $search = $request->input('search');
        if (!is_string($search)) {
            $search = '';
        }

        $search = trim($search);
        $sort = $request->input('sort', 'id');

        $direction = $request->input('direction', 'desc');

        $allowedSorts = [
            'id',
            'name',
            'price',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'id';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $produktList = Produkt::query()
            ->select([
                'id',
                'name',
                'price',
                'descript',
                'quantity'
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('price', 'like', "%{$search}%")
                        ->orWhere('descript', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

                 // Transform Eloquent models into ProductData DTOs
            $produktList->through(
                fn (Produkt $produkt) => ProductData::from([
                    'produktID' => $produkt->id,
                    'name'      => $produkt->name,
                    'price'     => $produkt->price,
                    'descript'  => $produkt->descript,
                    'quantity'=> $produkt->quantity,
                ])
            );

        return [
            'produktList' => $produktList,
            'filters' => [
                'search' => $search,
                'sort' => $sort,
                'direction' => $direction,
                'per_page' => $perPage,
            ],
        ];
    }
}
