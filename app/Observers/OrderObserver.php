<?php

namespace App\Observers;

use App\Events\OrderInfo\OrderCreated;
use App\Events\OrderInfo\OrderUpdated;
use App\Models\Order;
use App\Events\OrderInfo\OrderDeleted;
use App\Events\OrderInfo\OrderStatusChanged;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        broadcast(new OrderCreated($order));

    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        broadcast(new OrderUpdated($order));

        if ($order->wasChanged('status')) {
            broadcast(new OrderStatusChanged($order));
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        broadcast(new OrderDeleted($order));
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
