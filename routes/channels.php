<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// User receives live updates for a specific chat recipient.
Broadcast::channel('chat.{receiver_uuid}', function (User $user, $receiver_uuid) {
    return $user->uuid == $receiver_uuid;
});

// User receives live updates for a specific order.
Broadcast::channel('order.{order}', function (User $user, Order $order) {
    return $user->id === $order->user_id;
});

// User receives live updates for all of their orders.
Broadcast::channel('orders.all.{receiver_uuid}', function (User $user, $receiver_uuid) {
    return $user->uuid === $receiver_uuid;
});

// Users with this permission receive updates for all orders.
Broadcast::channel('orders.all', function (User $user) {
    return $user->hasPermissionTo('canGetAllOrderChat');
});
