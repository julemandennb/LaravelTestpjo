
<script setup>
import { Head, Link ,router} from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import {formatStatus,formatDato} from '@/help/FormatFun.js';
import Nav from  '@/Pages/Shop/Customer/Components/Nav.vue'

defineOptions({
    layout: ShopLayout,
});

defineProps({
    customer: Object,
    statistics: Object,
    recentOrders: Array,
});

</script>

<template>
    <Head title="Customer Dashboard" />

    <div class="min-h-screen bg-gray-50 px-5 py-12">
        <div class="mx-auto max-w-7xl">

            <!-- Welcome -->
            <div class="mb-10">
                <p class="font-semibold text-orange-500">
                    Customer Dashboard
                </p>

                <h1 class="mt-2 text-3xl font-black text-gray-900 md:text-4xl">
                    Welcome, {{ customer.name }}!
                </h1>

                <p class="mt-2 text-gray-500">
                    Manage your account and keep track of your orders.
                </p>
            </div>

            <Nav menuOn="Dashboard" />

            <!-- Statistics -->
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">

                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="text-sm font-medium text-gray-500">
                        Total Orders
                    </div>

                    <p class="mt-3 text-3xl font-black text-gray-900">
                        {{ statistics.total_orders }}
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        All your orders
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="text-sm font-medium text-gray-500">
                        Processing Orders
                    </div>

                    <p class="mt-3 text-3xl font-black text-orange-500">
                        {{ statistics.processing_orders }}
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        Orders being prepared or delivered
                    </p>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="text-sm font-medium text-gray-500">
                        Completed Orders
                    </div>

                    <p class="mt-3 text-3xl font-black text-green-600">
                        {{ statistics.completed_orders }}
                    </p>

                    <p class="mt-2 text-sm text-gray-500">
                        Successfully delivered
                    </p>
                </div>

            </div>

            <!-- Customer Information -->
            <div class="mt-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h2 class="text-xl font-black text-gray-900">
                            Account Information
                        </h2>

                        <p class="mt-2 text-sm text-gray-500">
                            {{ customer.name }}
                        </p>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ customer.email }}
                        </p>
                    </div>

                    <Link
                        :href="route('customer.profile.edit')"
                        class="rounded-xl bg-gray-900 px-5 py-3 text-center font-semibold text-white hover:bg-orange-500"
                    >
                        Edit Profile
                    </Link>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="mt-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">

                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl font-black text-gray-900">
                        Recent Orders
                    </h2>

                    <Link
                        :href="route('shop.tracking.index')"
                        class="text-sm font-semibold text-orange-600 hover:underline"
                    >
                        Track an order →
                    </Link>
                </div>

                <div
                    v-if="recentOrders.length"
                    class="mt-6 overflow-x-auto"
                >
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-100 text-sm text-gray-500">
                                <th class="py-4 pr-4">Order Number</th>
                                <th class="py-4 pr-4">Date</th>
                                <th class="py-4 pr-4">Total</th>
                                <th class="py-4 pr-4">Status</th>
                                <th class="py-4">Tracking</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="order in recentOrders"
                                :key="order.uuid"
                                class="border-b border-gray-50 text-sm"
                            >
                                <td class="py-4 pr-4 font-semibold text-gray-900">
                                    {{ order.uuid }}
                                </td>

                                <td class="py-4 pr-4 text-gray-500">
                                    {{ formatDato(order.created_at) }}
                                </td>

                                <td class="py-4 pr-4 font-semibold text-gray-900">
                                    {{ order.total_price }}
                                </td>

                                <td class="py-4 pr-4">
                                    <span class="rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700">
                                        {{ formatStatus(order.status) }}
                                    </span>
                                </td>

                                <td class="py-4">
                                    <Link
                                        :href="route('shop.tracking.index', {
                                            tracking_number: order.tracking_code
                                        })"
                                        class="font-semibold text-orange-600 hover:underline"
                                    >
                                        Track order
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-else
                    class="mt-6 rounded-xl bg-gray-50 p-8 text-center"
                >
                    <div class="text-4xl">📦</div>

                    <h3 class="mt-3 font-bold text-gray-900">
                        No orders yet
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Your orders will appear here when you make a purchase.
                    </p>

                    <Link
                        :href="route('shop.produkt.index')"
                        class="mt-5 inline-block rounded-xl bg-orange-500 px-6 py-3 font-semibold text-white hover:bg-orange-600"
                    >
                        Start Shopping
                    </Link>
                </div>

            </div>

        </div>
    </div>
</template>
