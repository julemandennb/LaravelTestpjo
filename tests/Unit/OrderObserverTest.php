<?php

namespace Tests\Unit;

use App\Enum\OrderStatus;
use App\Events\OrderInfo\OrderCreated;
use App\Events\OrderInfo\OrderDeleted;
use App\Events\OrderInfo\OrderStatusChanged;
use App\Events\OrderInfo\OrderUpdated;
use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderObserverTest extends TestCase
{
    public function test_created_broadcasts_order_created(): void
    {
        Event::fake();
        $order = new Order(['status' => OrderStatus::NEW]);
        $order->uuid = (string) Str::uuid();

        (new OrderObserver())->created($order);

        Event::assertDispatched(OrderCreated::class, function (OrderCreated $event) use ($order): bool {
            return $event->order === $order;
        });
    }

    public function test_deleted_broadcasts_order_deleted(): void
    {
        Event::fake();
        $order = new Order(['status' => OrderStatus::NEW]);
        $order->uuid = (string) Str::uuid();

        (new OrderObserver())->deleted($order);

        Event::assertDispatched(OrderDeleted::class, function (OrderDeleted $event) use ($order): bool {
            return $event->order === $order;
        });
    }

    public function test_updated_broadcasts_order_updated_without_status_changed(): void
    {
        Event::fake();
        $order = new Order(['status' => OrderStatus::NEW, 'total_price' => 100]);
        $order->uuid = (string) Str::uuid();
        $order->syncOriginal();
        $order->total_price = 200;
        $order->syncChanges();

        (new OrderObserver())->updated($order);

        Event::assertDispatched(OrderUpdated::class);
        Event::assertNotDispatched(OrderStatusChanged::class);
    }

    public function test_updated_broadcasts_status_changed_when_status_changed(): void
    {
        Event::fake();
        $order = new Order(['status' => OrderStatus::NEW]);
        $order->uuid = (string) Str::uuid();
        $order->syncOriginal();
        $order->status = OrderStatus::PROCESSING;
        $order->syncChanges();

        (new OrderObserver())->updated($order);

        Event::assertDispatched(OrderUpdated::class);
        Event::assertDispatched(OrderStatusChanged::class, function (OrderStatusChanged $event) use ($order): bool {
            return $event->order === $order;
        });
    }
}
