 <x-mail::message>
# Thank you for your order!

Hello {{ $order->name }},

We're happy to let you know that your order has been successfully placed!

## Your order number

<x-mail::panel>
**{{ $order->uuid }}**
</x-mail::panel>

## Your tracking code

<x-mail::panel>
# {{ $order->tracking_code }}
</x-mail::panel>

**Keep this tracking code!** You can use it to check your order status, see your purchased products, and view your order history.

<x-mail::button :url="route('shop.tracking.index', ['tracking_number' => $order->tracking_code])">
Track your order
</x-mail::button>

We'll start processing your order as soon as possible.

Thank you for shopping with us. We appreciate your business!

<x-mail::button :url="route('shop')">
Visit our shop
</x-mail::button>

Best regards,

**{{ config('app.name') }}**
</x-mail::message>
