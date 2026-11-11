<?php
namespace App\Actions\Produkts;

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
