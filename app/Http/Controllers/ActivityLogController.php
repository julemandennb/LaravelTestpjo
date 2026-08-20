<?php

namespace App\Http\Controllers;

use App\Enum\LogName;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __invoke(Request $request)
    {
        $perPage = min((int) $request->input('per_page', 10), 100);

        $search = $request->input('search');
        $logtype = $request->input('logtype','all');

        $allowedSorts = [
            'log_name',
            'subject_id',
            'description',
            'event',
            'causer_id',
            'updated_at',
        ];

        $sort = in_array($request->input('sort'), $allowedSorts, true)
            ? $request->input('sort')
            : 'updated_at';

        $direction = $request->input('direction') === 'asc'
            ? 'asc'
            : 'desc';

        $activitys = Activity::query()
        ->with('causer')
        ->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('description', 'like', "%{$search}%")
                    ->orWhere('log_name', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('subject_id', 'like', "%{$search}%")
                    ->orWhere('causer_id', 'like', "%{$search}%");
            });
        })
        ->when($logtype, function($query, $logtype){
                if($logtype !="all")
                    $query->where('log_name',$logtype);
            })
        ->orderBy($sort, $direction)
        ->paginate($perPage)
        ->withQueryString();

       return Inertia::render('ActivityLog/index', [
            'activitys' => $activitys,
            'logNames' => LogName::list(),
            'filters' => [
                'search' => $search,
                'sort' => $sort,
                'direction' => $direction,
                'per_page' => $perPage,
                'logtype' => $logtype
            ],
        ]);


    }
}
