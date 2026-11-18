
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import { formatPrice } from '@/help/FormatFun';
import { addToCart } from '@/help/Cart';

defineOptions({
    layout: ShopLayout,
});

const props = defineProps({
    produkt: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head :title="`${produkt.name} - Product Details`" />

    <div class="min-h-screen bg-gray-50">

        <!-- Breadcrumb -->
        <section class="mx-auto max-w-7xl px-5 pt-8 lg:px-8">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <Link
                    :href="route('shop.produkt.index')"
                    class="transition hover:text-orange-500"
                >
                    Products
                </Link>

                <span>/</span>

                <span class="font-semibold text-gray-900">
                    {{ produkt.name }}
                </span>
            </div>
        </section>

        <!-- Product Details -->
        <section class="mx-auto max-w-7xl px-5 py-12 lg:px-8">

            <div class="grid gap-12 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm md:grid-cols-2 md:p-10">

                <!-- Product Image -->
                <div class="flex min-h-[400px] items-center justify-center rounded-2xl bg-gray-100">

                    <div class="text-center">
                        <span class="text-8xl">📦</span>

                        <p class="mt-4 text-sm text-gray-400">
                            Product image
                        </p>
                    </div>

                </div>

                <!-- Product Information -->
                <div class="flex flex-col justify-center">

                    <span class="w-fit rounded-full bg-orange-100 px-4 py-2 text-xs font-bold uppercase tracking-widest text-orange-600">
                        Product #{{ produkt.produktID ?? produkt.id }}
                    </span>

                    <h1 class="mt-5 text-4xl font-black tracking-tight text-gray-900 md:text-5xl">
                        {{ produkt.name }}
                    </h1>

                    <!-- Price -->
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-500">
                            Price
                        </p>

                        <p class="mt-2 text-3xl font-black text-orange-500">
                            {{ formatPrice(produkt.price) }}
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="mt-8 border-t border-gray-100 pt-6">

                        <h2 class="text-lg font-bold text-gray-900">
                            Product Description
                        </h2>

                        <p class="mt-3 whitespace-pre-line leading-7 text-gray-600">
                            {{ produkt.descript || 'No description available.' }}
                        </p>

                    </div>

                    <!-- Quantity -->
                    <div class="mt-8 rounded-xl bg-gray-50 p-5">

                        <p class="text-sm font-semibold text-gray-700">
                            Availability
                        </p>

                        <div class="mt-2 flex items-center gap-2">

                            <span
                                class="h-3 w-3 rounded-full"
                                :class="produkt.quantity > 0
                                    ? 'bg-green-500'
                                    : 'bg-red-500'"
                            ></span>

                            <span
                                class="font-semibold"
                                :class="produkt.quantity > 0
                                    ? 'text-green-600'
                                    : 'text-red-600'"
                            >
                                {{ produkt.quantity > 0
                                    ? `${produkt.quantity} available`
                                    : 'Out of stock' }}
                            </span>

                        </div>

                    </div>

                    <div class="mt-8 flex w-full items-center justify-between gap-4">

                        <!-- Left -->
                        <Link
                            :href="route('shop.produkt.index')"
                            class="inline-flex items-center justify-center rounded-full bg-gray-900 px-8 py-4 text-sm font-bold text-white transition hover:bg-orange-500"
                        >
                            ← Back to Products
                        </Link>

                        <!-- Right -->
                        <button
                            type="button"
                            v-if="produkt.quantity > 0"
                            @click="addToCart(produkt)"
                            class="inline-flex items-center justify-center rounded-full bg-orange-500 px-8 py-4 text-sm font-bold text-white transition hover:bg-orange-600"
                        >
                            Add to Cart →
                        </button>

                    </div>

                </div>

            </div>

        </section>

    </div>
</template>
