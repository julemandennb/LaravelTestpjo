
<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import { formatPrice } from '@/help/FormatFun';

defineOptions({
    layout: ShopLayout,
});

const props = defineProps({
    cart: {
        type: Array,
        default: () => [],
    },
});

const total = () => {
    return props.cart.reduce((sum, produkt) => {
        return sum + produkt.price * produkt.quantity;
    }, 0);
};

const removeFromCart = (id) => {
    router.delete(route('shop.cart.remove', id), {
        preserveScroll: true,
    });
};

const clearCart = () => {
    router.delete(route('shop.cart.clear'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Shopping Cart" />

    <div class="mx-auto max-w-6xl px-4 py-10">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">
            Shopping Cart
        </h1>

        <!-- Empty cart -->
        <div
            v-if="cart.length === 0"
            class="rounded-xl bg-white p-10 text-center shadow"
        >
            <h2 class="mb-3 text-xl font-semibold">
                Your cart is empty
            </h2>

            <p class="mb-6 text-gray-500">
                You haven't added any products yet.
            </p>

            <Link
                :href="route('shop.produkt.index')"
                class="rounded-lg bg-orange-500 px-6 py-3 font-semibold text-white hover:bg-orange-600"
            >
                Continue Shopping
            </Link>
        </div>

        <!-- Cart with products -->
        <div v-else class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-4 lg:col-span-2">
                <div
                    v-for="produkt in cart"
                    :key="produkt.produktID"
                    class="flex items-center justify-between gap-4 rounded-xl bg-white p-5 shadow"
                >
                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ produkt.name }}
                        </h2>

                        <p class="mt-1 text-gray-500">
                            {{ formatPrice(produkt.price) }}
                        </p>

                        <p class="mt-2 text-sm text-gray-600">
                            Quantity: {{ produkt.quantity }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="font-semibold text-gray-900">
                            {{ formatPrice(produkt.price * produkt.quantity) }}
                        </p>

                        <button
                            type="button"
                            @click="removeFromCart(produkt.produktID)"
                            class="mt-3 text-sm font-medium text-red-500 hover:text-red-700"
                        >
                            Remove
                        </button>
                    </div>
                </div>

                <button
                    type="button"
                    @click="clearCart"
                    class="text-sm font-medium text-red-500 hover:text-red-700"
                >
                    Clear Cart
                </button>
            </div>

            <!-- Order summary -->
            <div class="h-fit rounded-xl bg-white p-6 shadow">
                <h2 class="mb-5 text-xl font-bold">
                    Order Summary
                </h2>

                <div class="flex justify-between border-b pb-4 text-gray-600">
                    <span>Subtotal</span>
                    <span>{{ formatPrice(total()) }}</span>
                </div>

                <div class="flex justify-between py-4 text-lg font-bold">
                    <span>Total</span>
                    <span>{{ formatPrice(total()) }}</span>
                </div>

                <button
                    type="button"
                    disabled
                    class="w-full cursor-not-allowed rounded-lg bg-gray-300 px-6 py-3 font-semibold text-gray-600"
                >
                    Checkout (Coming Soon)
                </button>

                <Link
                    :href="route('shop.produkt.index')"
                    class="mt-4 block text-center text-sm text-orange-600 hover:underline"
                >
                    Continue Shopping
                </Link>
            </div>
        </div>
    </div>
</template>
