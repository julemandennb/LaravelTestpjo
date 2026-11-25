
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import {formatDato,formatPrice,formatStatus} from '@/help/FormatFun.js';


defineOptions({
    layout: ShopLayout,
});

defineProps({
    order: Object,
});

const statusClass = (status) => {
    if (status === 'completed') return 'bg-green-100 text-green-700';
    if (status === 'cancelled') return 'bg-red-100 text-red-700';

    return 'bg-blue-100 text-blue-700';
};
</script>

<template>
    <Head title="Order Details" />

    <div class="mx-auto max-w-5xl px-4 py-12">
        <Link
            :href="route('customer.orders.index')"
            class="text-sm text-gray-500 hover:text-black"
        >
            ← Back to my orders
        </Link>

        <div class="mb-8 mt-5 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Order Details
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Order #{{ order.uuid }}
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Placed {{ formatDato(order.created_at) }}
                </p>
            </div>

            <span
                class="inline-flex w-fit rounded-full px-4 py-2 text-sm font-semibold"
                :class="statusClass(order.status)"
            >
                {{ formatStatus(order.status) }}
            </span>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <section class="rounded-2xl border border-gray-200 bg-white p-6">
                    <h2 class="mb-5 text-xl font-bold text-gray-900">
                        Ordered products
                    </h2>

                    <div
                        v-for="product in order.products"
                        :key="product.id"
                        class="flex justify-between gap-4 border-b border-gray-100 py-4 last:border-0"
                    >
                        <div>
                            <h3 class="font-semibold text-gray-900">
                                {{ product.name }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Quantity: {{ product.quantity }}
                            </p>
                        </div>

                        <p class="font-semibold text-gray-900">
                            {{ formatPrice(product.price * product.quantity) }}
                        </p>
                    </div>

                    <div class="mt-5 flex justify-between border-t pt-5">
                        <span class="text-lg font-bold text-gray-900">
                            Total
                        </span>

                        <span class="text-lg font-bold text-gray-900">
                            {{ formatPrice(order.total_price) }}
                        </span>
                    </div>
                </section>

                <section class="rounded-2xl border border-gray-200 bg-white p-6">
                    <h2 class="mb-4 text-xl font-bold text-gray-900">
                        Delivery information
                    </h2>

                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Name:</strong> {{ order.name }}</p>
                        <p><strong>Email:</strong> {{ order.email }}</p>
                        <p><strong>Phone:</strong> {{ order.phone }}</p>
                        <p><strong>Address:</strong> {{ order.address }}</p>
                        <p><strong>Postal code:</strong> {{ order.postNr }}</p>
                    </div>
                </section>
            </div>

            <aside class="h-fit rounded-2xl border border-gray-200 bg-white p-6">
                <h2 class="mb-4 text-xl font-bold text-gray-900">
                    Shipment
                </h2>

                <p class="text-sm text-gray-500">
                    Tracking number
                </p>

                <p class="mt-1 break-all font-semibold text-gray-900">
                    {{ order.tracking_code }}
                </p>

                <Link
                    :href="route('shop.tracking.index', {
                        tracking_number: order.tracking_code
                    })"
                    class="mt-5 block rounded-lg bg-gray-900 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-gray-700"
                >
                    Track my order
                </Link>
            </aside>
        </div>
    </div>
</template>
