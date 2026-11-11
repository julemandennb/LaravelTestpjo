
<script setup>
import { Head } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';

defineOptions({
    layout: ShopLayout,
});
</script>

<template>
    <Head title="Shop - Discover Something Special" />

    <!-- Hero -->
    <section class="relative overflow-hidden bg-[#f4f0eb]">

        <div class="mx-auto grid max-w-7xl items-center gap-8 px-5 py-16 md:grid-cols-2 md:py-24 lg:px-8">

            <div class="relative z-10">

                <span class="mb-5 inline-block rounded-full bg-orange-100 px-4 py-2 text-xs font-bold uppercase tracking-widest text-orange-700">
                    New collection 2026
                </span>

                <h1 class="max-w-xl text-5xl font-black leading-tight tracking-tight text-gray-900 md:text-6xl lg:text-7xl">
                    Discover your
                    <span class="text-orange-500">style.</span>
                </h1>

                <p class="mt-6 max-w-md text-base leading-7 text-gray-600">
                    Find products you love. Explore our latest collection
                    and discover something special for every day.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">

                    <a
                        :href="route('shop.produkt.index')"
                        class="rounded-full bg-gray-900 px-8 py-4 text-sm font-bold text-white transition hover:bg-orange-500"
                    >
                        Shop Collection →
                    </a>

                </div>

                <div class="mt-12 flex gap-10">

                    <div>
                        <p class="text-2xl font-black">500+</p>
                        <p class="mt-1 text-xs text-gray-500">Products</p>
                    </div>

                    <div>
                        <p class="text-2xl font-black">10k+</p>
                        <p class="mt-1 text-xs text-gray-500">Happy customers</p>
                    </div>

                    <div>
                        <p class="text-2xl font-black">24/7</p>
                        <p class="mt-1 text-xs text-gray-500">Support</p>
                    </div>

                </div>
            </div>

            <div class="relative">

                <img
                    src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200"
                    alt="Shop collection"
                    class="h-[350px] w-full rounded-3xl object-cover shadow-xl md:h-[520px]"
                />

                <div class="absolute -bottom-5 -left-3 rounded-2xl bg-white p-5 shadow-xl md:-left-8">

                    <p class="text-xs font-medium text-gray-500">
                        Discover more
                    </p>

                    <p class="mt-1 text-lg font-black">
                        Your next favourite.
                    </p>

                </div>
            </div>

        </div>
    </section>

    <!-- About -->
    <section
        id="about"
        class="bg-gray-900 px-5 py-20 text-center text-white"
    >

        <div class="mx-auto max-w-2xl">

            <p class="text-xs font-bold uppercase tracking-widest text-orange-400">
                Welcome to our store
            </p>

            <h2 class="mt-4 text-3xl font-black md:text-4xl">
                Good products. Great experiences.
            </h2>

            <p class="mt-5 leading-7 text-gray-400">
                We believe shopping should be simple, enjoyable and inspiring.
                Explore our collection and find your next favourite product.
            </p>

            <a
                :href="route('shop.produkt.index')"
                class="mt-8 inline-block rounded-full bg-orange-500 px-8 py-4 text-sm font-bold text-white transition hover:bg-orange-600"
            >
                Explore Products →
            </a>

        </div>
    </section>
</template>
