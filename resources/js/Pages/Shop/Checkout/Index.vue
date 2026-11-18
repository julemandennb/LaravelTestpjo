
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
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

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    postal_code: '',
    city: '',
});

const total = computed(() => {
    return props.cart.reduce((sum, produkt) => {
        return sum + produkt.price * produkt.quantity;
    }, 0);
});

const placeOrder = () => {
    form.post(route('shop.checkout.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Checkout" />

    <div class="mx-auto max-w-6xl px-4 py-10">
        <h1 class="mb-8 text-3xl font-bold text-gray-900">
            Checkout
        </h1>

               <p
            v-if="form.errors.checkout"
            class="mb-4 text-sm text-red-500"
        >
            {{ form.errors.checkout }}
        </p>

        <div
            v-if="cart.length === 0"
            class="rounded-xl bg-white p-10 text-center shadow"
        >
            <h2 class="mb-3 text-xl font-semibold">
                Your cart is empty
            </h2>

            <p class="mb-6 text-gray-500">
                Add some products before proceeding to checkout.
            </p>

            <Link
                :href="route('shop.produkt.index')"
                class="inline-block rounded-lg bg-orange-500 px-6 py-3 font-semibold text-white hover:bg-orange-600"
            >
                Continue Shopping
            </Link>
        </div>

        <form
            v-else
            @submit.prevent="placeOrder"
            class="grid gap-8 lg:grid-cols-3"
        >
            <!-- Customer information -->
            <div class="space-y-8 lg:col-span-2">
                <section class="rounded-xl bg-white p-6 shadow">
                    <h2 class="mb-6 text-xl font-bold">
                        Customer Information
                    </h2>

                    <div class="space-y-5">
                        <div>
                            <label
                                for="name"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Full Name
                            </label>

                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                autocomplete="name"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                required
                            />

                            <p v-if="form.errors.name" class="mt-1 text-sm text-red-500">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div>
                            <label
                                for="email"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Email Address
                            </label>

                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                autocomplete="email"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                required
                            />

                            <p v-if="form.errors.email" class="mt-1 text-sm text-red-500">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <div>
                            <label
                                for="phone"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Phone Number
                            </label>

                            <input
                                id="phone"
                                v-model="form.phone"
                                type="tel"
                                autocomplete="tel"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                required
                            />

                            <p v-if="form.errors.phone" class="mt-1 text-sm text-red-500">
                                {{ form.errors.phone }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Shipping address -->
                <section class="rounded-xl bg-white p-6 shadow">
                    <h2 class="mb-6 text-xl font-bold">
                        Shipping Address
                    </h2>

                    <div class="space-y-5">
                        <div>
                            <label
                                for="address"
                                class="mb-2 block text-sm font-medium text-gray-700"
                            >
                                Street Address
                            </label>

                            <input
                                id="address"
                                v-model="form.address"
                                type="text"
                                autocomplete="street-address"
                                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                required
                            />

                            <p v-if="form.errors.address" class="mt-1 text-sm text-red-500">
                                {{ form.errors.address }}
                            </p>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label
                                    for="postal_code"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Postal Code
                                </label>

                                <input
                                    id="postal_code"
                                    v-model="form.postal_code"
                                    type="text"
                                    autocomplete="postal-code"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                    required
                                />

                                <p v-if="form.errors.postal_code" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.postal_code }}
                                </p>
                            </div>

                            <div>
                                <label
                                    for="city"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    City
                                </label>

                                <input
                                    id="city"
                                    v-model="form.city"
                                    type="text"
                                    autocomplete="address-level2"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500"
                                    required
                                />

                                <p v-if="form.errors.city" class="mt-1 text-sm text-red-500">
                                    {{ form.errors.city }}
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Order summary -->
            <aside class="h-fit rounded-xl bg-white p-6 shadow">
                <h2 class="mb-6 text-xl font-bold">
                    Your Order
                </h2>

                <div class="space-y-4">
                    <div
                        v-for="produkt in cart"
                        :key="produkt.produktID"
                        class="flex justify-between gap-4 text-sm"
                    >
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ produkt.name }}
                            </p>

                            <p class="text-gray-500">
                                Quantity: {{ produkt.quantity }}
                            </p>
                        </div>

                        <p class="whitespace-nowrap font-medium">
                            {{ formatPrice(produkt.price * produkt.quantity) }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 border-t pt-5">
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total</span>
                        <span>{{ formatPrice(total) }}</span>
                    </div>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="mt-6 w-full rounded-lg bg-orange-500 px-6 py-3 font-semibold text-white transition hover:bg-orange-600 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{ form.processing ? 'Processing...' : 'Place Order' }}
                </button>

                <Link
                    :href="route('shop.cart.index')"
                    class="mt-4 block text-center text-sm text-orange-600 hover:underline"
                >
                    Back to Cart
                </Link>
            </aside>
        </form>
    </div>
</template>
