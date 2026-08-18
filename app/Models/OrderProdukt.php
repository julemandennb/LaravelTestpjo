<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class OrderProdukt extends Pivot
{
    use LogsActivity;

    protected $table = 'order_produkts';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'order_id',
        'produkt_id',
        'quantity',
        'price',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
    }
}
