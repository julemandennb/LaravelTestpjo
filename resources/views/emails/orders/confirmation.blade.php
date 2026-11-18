
<x-mail::message>
# Thank you for your order!

Hello {{ $order->name }},

We're happy to let you know that your order has been successfully placed!

Your order number is:

<x-mail::panel>
**{{ $order->uuid }}**
</x-mail::panel>

We'll start processing your order as soon as possible.

Thank you for shopping with us. We appreciate your business!

<x-mail::button :url="route('shop')">
Visit our shop
</x-mail::button>

Best regards,

**{{ config('app.name') }}**
</x-mail::message>
