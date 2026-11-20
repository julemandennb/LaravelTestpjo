
<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import {formatDato,formatPrice,formatStatus} from '@/help/FormatFun.js';

defineOptions({
    layout: ShopLayout,
});

const props = defineProps({
    tracking: {
        type: Object,
        default: null,
    },
    error: {
        type: String,
        default: null,
    },
});

const trackingNumber = ref(props.tracking?.tracking_number ?? '');
const loading = ref(false);

const steps = [
    {
        key: 'new',
        label: 'Order Received',
        description: 'Your order has been successfully received.',
        icon: '📋',
    },
    {
        key: 'processing',
        label: 'Processing',
        description: 'Your order is being prepared.',
        icon: '📦',
    },
    {
        key: 'packed',
        label: 'Packed',
        description: 'Your order has been packed and is ready for shipment.',
        icon: '📦',
    },
    {
        key: 'shipped',
        label: 'Shipped',
        description: 'Your order has been shipped.',
        icon: '🚚',
    },
    {
        key: 'completed',
        label: 'Delivered',
        description: 'Your order has been delivered successfully.',
        icon: '✅',
    },
];

const currentStatus = computed(() => {
    return String(props.tracking?.status ?? '')
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '_');
});

const currentStep = computed(() => {
    if (currentStatus.value === 'cancelled') return -1;

    return steps.findIndex(
        step => step.key === currentStatus.value
    );
});

function isCompleted(index) {
    return currentStep.value >= index && currentStep.value !== -1;
}


function trackOrder() {
    if (!trackingNumber.value.trim()) return;

    loading.value = true;

    router.get(
        route('shop.tracking.index'),
        {
            tracking_number: trackingNumber.value.trim(),
        },
        {
            preserveScroll: true,
            replace: true,
            onFinish: () => {
                loading.value = false;
            },
        }
    );
}
</script>

<template>
    <Head title="Track Your Order" />

    <div class="min-h-screen bg-gray-50">

        <!-- Hero -->
        <section class="bg-[#f4f0eb] px-5 py-16 md:py-24">
            <div class="mx-auto max-w-3xl text-center">

                <span
                    class="inline-block rounded-full bg-orange-100 px-4 py-2 text-xs font-bold uppercase tracking-widest text-orange-700"
                >
                    Order Tracking
                </span>

                <h1 class="mt-6 text-4xl font-black tracking-tight text-gray-900 md:text-6xl">
                    Track your
                    <span class="text-orange-500">order.</span>
                </h1>

                <p class="mx-auto mt-5 max-w-xl leading-7 text-gray-600">
                    Wondering where your package is?
                    Enter your tracking number and follow your order.
                </p>

                <!-- Search Form -->
                <form
                    @submit.prevent="trackOrder"
                    class="mx-auto mt-10 max-w-xl rounded-2xl bg-white p-3 shadow-lg"
                >
                    <div class="flex flex-col gap-3 sm:flex-row">

                        <input
                            v-model="trackingNumber"
                            type="text"
                            placeholder="Enter tracking number..."
                            required
                            class="min-w-0 flex-1 rounded-xl border-gray-200 px-5 py-4 focus:border-orange-500 focus:ring-orange-500"
                        />

                        <button
                            type="submit"
                            :disabled="loading || !trackingNumber.trim()"
                            class="rounded-xl bg-orange-500 px-8 py-4 font-bold text-white transition hover:bg-orange-600 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            {{ loading ? 'Tracking...' : 'Track Order →' }}
                        </button>

                    </div>
                </form>

                <p class="mt-4 text-sm text-gray-500">
                    Please enter your tracking number correctly.
                </p>

            </div>
        </section>

        <!-- Tracking Results -->
        <section
            v-if="tracking"
            class="mx-auto max-w-5xl px-5 py-12"
        >

            <!-- Cancelled Order -->
            <div
                v-if="currentStatus === 'cancelled'"
                class="mb-8 rounded-xl border border-red-200 bg-red-50 p-5"
            >
                <h3 class="font-bold text-red-700">
                    Order Cancelled
                </h3>

                <p class="mt-2 text-sm text-red-600">
                    This order has been cancelled.
                    Please contact our support team if you need assistance.
                </p>
            </div>

            <!-- Order Information -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">

                    <div>
                        <p class="text-sm text-gray-500">
                            Tracking Number
                        </p>

                        <h2 class="mt-1 text-xl font-black text-gray-900">
                            {{ tracking.tracking_number }}
                        </h2>
                    </div>

                    <div>
                        <span
                            class="inline-flex rounded-full bg-orange-100 px-4 py-2 text-sm font-bold capitalize text-orange-700"
                        >
                            {{ formatStatus(tracking.status) }}
                        </span>
                    </div>

                </div>

                <div class="mt-8 border-t border-gray-100 pt-6">
                    <p class="text-sm text-gray-500">
                        Last Updated
                    </p>

                    <p class="mt-2 text-lg font-bold text-gray-900">
                        {{ formatDato(tracking.updated_at) }}
                    </p>
                </div>

            </div>

            <!-- Order Items -->
            <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:p-8">

                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-black text-gray-900">
                        Order Items
                    </h2>

                    <span class="text-sm text-gray-500">
                        {{ tracking.items?.length ?? 0 }} items
                    </span>
                </div>

                <div
                    v-if="tracking.items?.length"
                    class="mt-6 divide-y divide-gray-100"
                >
                    <div
                        v-for="(item, index) in tracking.items"
                        :key="item.id ?? index"
                        class="flex flex-col justify-between gap-4 py-5 sm:flex-row sm:items-center"
                    >

                        <div class="flex items-center gap-4">

                            <img
                                v-if="item.image"
                                :src="item.image"
                                :alt="item.name ?? item.title ?? 'Product'"
                                class="h-20 w-20 rounded-xl border border-gray-100 object-cover"
                            />

                            <div
                                v-else
                                class="flex h-20 w-20 items-center justify-center rounded-xl bg-gray-100 text-3xl"
                            >
                                📦
                            </div>

                            <div>
                                <h3 class="font-bold text-gray-900">
                                    {{ item.name ?? item.title ?? item.product_name ?? 'Product' }}
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Quantity:
                                    {{ item.pivot?.quantity ?? item.quantity ?? 1 }}
                                </p>
                            </div>

                        </div>

                        <div class="sm:text-right">
                            <p class="font-bold text-gray-900">
                                {{ formatPrice(item.pivot?.price)}}
                            </p>
                        </div>

                    </div>
                </div>

                <div
                    v-else
                    class="mt-6 rounded-xl bg-gray-50 p-6 text-center text-sm text-gray-500"
                >
                    No order items are available.
                </div>

            </div>

            <!-- Order Progress Timeline -->
            <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:p-8">

                <h2 class="text-2xl font-black text-gray-900">
                    Order Progress
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    Follow your order as it moves toward delivery.
                </p>

                <div class="mt-8 space-y-8">

                    <div
                        v-for="(step, index) in steps"
                        :key="step.key"
                        class="relative flex gap-5"
                    >

                        <!-- Timeline Line -->
                        <div
                            v-if="index !== steps.length - 1"
                            class="absolute left-5 top-12 h-full w-0.5"
                            :class="isCompleted(index + 1)
                                ? 'bg-orange-500'
                                : 'bg-gray-200'"
                        ></div>

                        <!-- Icon -->
                        <div
                            class="relative z-10 flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-lg"
                            :class="isCompleted(index)
                                ? 'bg-orange-500 text-white'
                                : 'bg-gray-100 text-gray-400'"
                        >
                            {{ step.icon }}
                        </div>

                        <!-- Description -->
                        <div class="pb-3">

                            <h3
                                class="font-bold"
                                :class="isCompleted(index)
                                    ? 'text-gray-900'
                                    : 'text-gray-400'"
                            >
                                {{ step.label }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                {{ step.description }}
                            </p>

                            <span
                                v-if="currentStep === index"
                                class="mt-2 inline-block rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-700"
                            >
                                Current Status
                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Order History -->
            <div class="mt-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:p-8">

                <h2 class="text-2xl font-black text-gray-900">
                    Order History
                </h2>

                <p class="mt-2 text-sm text-gray-500">
                    See the history of your order status updates.
                </p>

                <div
                    v-if="tracking.history?.length"
                    class="mt-8 space-y-6"
                >

                    <div
                        v-for="(event, index) in tracking.history"
                        :key="index"
                        class="flex gap-4"
                    >

                        <div class="flex flex-col items-center">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-orange-100 text-lg">
                                📦
                            </div>

                            <div
                                v-if="index !== tracking.history.length - 1"
                                class="mt-2 h-full min-h-8 w-0.5 bg-gray-200"
                            ></div>

                        </div>

                        <div class="flex-1 pb-5">

                            <h3 class="font-bold text-gray-900">
                                {{ formatStatus(event.status) }}
                            </h3>

                            <p
                                v-if="event.old_status"
                                class="mt-1 text-sm text-gray-500"
                            >
                                Previous status:
                                {{ formatStatus(event.old_status) }}
                            </p>

                            <p class="mt-2 text-xs text-gray-400">
                                {{ formatDato(event.created_at) }}
                            </p>

                        </div>

                    </div>

                </div>

                <div
                    v-else
                    class="mt-6 rounded-xl bg-gray-50 p-6 text-center text-sm text-gray-500"
                >
                    No order history is available yet.
                </div>

            </div>

        </section>

        <!-- Error Message -->
        <section
            v-else-if="error"
            class="mx-auto max-w-xl px-5 py-12"
        >
            <div class="rounded-2xl border border-red-200 bg-red-50 p-8 text-center">

                <div class="text-5xl">🔍</div>

                <h2 class="mt-4 text-xl font-black text-gray-900">
                    Tracking Not Found
                </h2>

                <p class="mt-3 text-sm leading-6 text-gray-600">
                    {{ error }}
                </p>

                <button
                    type="button"
                    @click="trackingNumber = ''"
                    class="mt-6 rounded-full bg-gray-900 px-6 py-3 font-bold text-white hover:bg-orange-500"
                >
                    Try Another Number
                </button>

            </div>
        </section>

        <!-- Initial Empty State -->
        <section
            v-else
            class="px-5 py-16 text-center"
        >
            <div class="text-5xl">📦</div>

            <h2 class="mt-5 text-xl font-bold text-gray-900">
                Your package is just a search away
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Enter your tracking number above to get started.
            </p>
        </section>

        <!-- Back to Shop -->
        <div class="pb-12 text-center">
            <Link
                :href="route('shop.produkt.index')"
                class="text-sm font-semibold text-orange-600 hover:underline"
            >
                ← Continue Shopping
            </Link>
        </div>

    </div>
</template>
