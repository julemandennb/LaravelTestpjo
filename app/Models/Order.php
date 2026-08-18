<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\OrderProdukt;
use App\Enum\OrderStatus;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory,LogsActivity;

    protected $fillable = [
        'user_id', // foreign key to User
        'total_price', // You may want to store the total price of the order

        'name',
        'email',
        'phone',
        'address',
        'postNr',
        'status'
    ];

    protected $casts = [
        'status' => OrderStatus::class
    ];

    // A single order belongs to a single user (one-to-many)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // user_id is the foreign key
    }

    // An order can have many products (many-to-many)
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Produkt::class,
            'order_produkts',
            'order_id',
            'produkt_id'
        )
        ->using(OrderProdukt::class)
        ->withPivot('id', 'quantity', 'price');
    }

    public function removeProductsExceptPivotIds($pivotIds): int
    {
        $query = OrderProdukt::where('order_id', $this->id);

        if ($pivotIds->isNotEmpty()) {
            $query->whereNotIn('id', $pivotIds);
        }

        $products = $query->get();

        foreach ($products as $product) {
            $product->delete();
        }

        return $products->count();
    }
}
