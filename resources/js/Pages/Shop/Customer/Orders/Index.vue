
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import {formatDato,formatPrice,formatStatus} from '@/help/FormatFun.js';


defineOptions({
    layout: ShopLayout,
});

defineProps({
    orders: Object,
});


const statusClass = (status) => {
    if (status === 'completed') return 'bg-green-100 text-green-700';
    if (status === 'cancelled') return 'bg-red-100 text-red-700';
    if (status === 'shipped') return 'bg-blue-100 text-blue-700';

    return 'bg-yellow-100 text-yellow-700';
};
</script>

<template>
    <Head title="My Orders" />

    <div class="mx-auto max-w-6xl px-4 py-12">
        <div class="mb-8">
            <Link
                :href="route('customer.dashboard')"
                class="text-sm text-gray-500 hover:text-black"
            >
                ← My Account
            </Link>

            <h1 class="mt-3 text-3xl font-bold text-gray-900">
                My Orders
            </h1>

            <p class="mt-2 text-gray-500">
                View and follow your previous orders.
            </p>
        </div>

        <div v-if="orders.data.length" class="space-y-4">
            <div
                v-for="order in orders.data"
                :key="order.uuid"
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm"
            >
                <div class="flex flex-col justify-between gap-4 sm:flex-row">
                    <div>
                        <p class="text-sm text-gray-500">Order number</p>
                        <p class="font-semibold text-gray-900">
                            {{ order.uuid }}
                        </p>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ formatDato(order.created_at) }}
                        </p>
                    </div>

                    <div>
                        <span
                            class="inline-flex rounded-full px-3 py-1 text-sm font-medium"
                            :class="statusClass(order.status)"
                        >
                            {{ formatStatus(order.status) }}
                        </span>
                    </div>
                </div>

                <div class="mt-5 flex flex-col justify-between gap-4 border-t border-gray-100 pt-5 sm:flex-row sm:items-center">
                    <div>
                        <p class="text-sm text-gray-500">Total</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ formatPrice(order.total_price) }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <Link
                            :href="route('shop.tracking.index', {
                                tracking_number: order.tracking_code
                            })"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold hover:bg-gray-50"
                        >
                            Track order
                        </Link>

                        <Link
                            :href="route('customer.orders.show', order.uuid)"
                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700"
                        >
                            View details
                        </Link>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 pt-4">
                <p class="text-sm text-gray-500">
                    Showing {{ orders.from }}–{{ orders.to }}
                    of {{ orders.total }} orders
                </p>

                <div class="flex gap-2">
                    <Link
                        v-if="orders.prev_page_url"
                        :href="orders.prev_page_url"
                        class="rounded-lg border px-4 py-2 text-sm hover:bg-gray-50"
                    >
                        Previous
                    </Link>

                    <Link
                        v-if="orders.next_page_url"
                        :href="orders.next_page_url"
                        class="rounded-lg border px-4 py-2 text-sm hover:bg-gray-50"
                    >
                        Next
                    </Link>
                </div>
            </div>
        </div>

        <div
            v-else
            class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center"
        >
            <h2 class="text-xl font-semibold text-gray-900">
                No orders yet
            </h2>

            <p class="mt-2 text-gray-500">
                Your orders will appear here when you make a purchase.
            </p>

            <Link
                :href="route('shop.produkt.index')"
                class="mt-6 inline-block rounded-lg bg-gray-900 px-6 py-3 font-semibold text-white hover:bg-gray-700"
            >
                Start shopping
            </Link>
        </div>
    </div>
</template>
