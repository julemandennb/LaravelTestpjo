<?php

namespace Tests\Feature;

use App\Enum\LogName;
use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;
use Spatie\Activitylog\CauserResolver;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_update_creates_activity(): void
{
    $user = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $user->id,
        'status' => OrderStatus::PROCESSING,
        'total_price' => 100,
    ]);

    $this->actingAs($user);
    $this->assertAuthenticated();
    $this->assertEquals($user->id, auth()->id());

    $order->update([
        'status' => OrderStatus::SHIPPED,
    ]);

    $activity = Activity::where('subject_id', $order->id)
    ->latest("id")->first();

    $this->assertNotNull($activity);

    $this->assertEquals(
        LogName::ORDER->value,
        $activity->log_name
    );



    $this->assertEquals(
        $user->id,
        $activity->causer_id
    );

    $this->assertEquals(
        $order->id,
        $activity->subject_id
    );

    $this->assertEquals(
        'updated',
        $activity->event
    );
}
}
