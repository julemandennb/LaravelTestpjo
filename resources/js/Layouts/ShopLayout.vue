
<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed, ref, watch, onBeforeUnmount } from 'vue';
import axios from 'axios';

const page = usePage();

const props = defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
    customDashboard: {
        type: Boolean,
        default: false,
    },
    memberItems: {
        type: Boolean,
        default: false,
    },
    backendDashboard: {
        type: Boolean,
        default: false,
    },
});

const search = ref('');
const searchResults = ref([]);
const searching = ref(false);

let searchTimeout = null;
let searchRequestId = 0;

// Cart
const cart = ref([]);

const cartCount = computed(() =>
    cart.value.reduce((total, item) => total + item.quantity, 0)
);

// Search products
watch(search, (value) => {
    clearTimeout(searchTimeout);

    const requestId = ++searchRequestId;

    if (!value.trim()) {
        searchResults.value = [];
        searching.value = false;
        return;
    }

    searching.value = true;

    searchTimeout = setTimeout(async () => {
        try {
            const response = await axios.get('/api/produkt-search', {
                params: {
                    search: value.trim(),
                },
            });

            if (requestId === searchRequestId) {
                searchResults.value = response.data;
            }
        } catch (error) {
            if (requestId === searchRequestId) {
                console.error('Product search failed:', error);
                searchResults.value = [];
            }
        } finally {
            if (requestId === searchRequestId) {
                searching.value = false;
            }
        }
    }, 300);
});

onBeforeUnmount(() => {
    clearTimeout(searchTimeout);
    searchRequestId++;
});

function clearSearch() {
    search.value = '';
    searchResults.value = [];
}

function scrollToCart() {
    document.getElementById('cart-section')?.scrollIntoView({
        behavior: 'smooth',
    });
}

const authUser = computed(() => page.props.auth?.user);
</script>

<template>
    <div class="min-h-screen bg-white text-gray-900">

        <!-- Header -->
        <header class="sticky top-0 z-40 border-b border-gray-100 bg-white/95 backdrop-blur">

            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 lg:px-8">

                <!-- Logo -->
                <Link
                    :href="route('shop')"
                    class="text-2xl font-black tracking-tight text-gray-900"
                >
                    YOUR<span class="text-orange-500">SHOP.</span>
                </Link>

                <!-- Navigation -->
                <nav class="hidden items-center gap-8 text-sm font-medium md:flex">

                    <Link :href="route('shop')" class="hover:text-orange-500">
                        Home
                    </Link>

                    <Link
                        :href="route('shop.produkt.index')"
                        class="hover:text-orange-500"
                    >
                        Shop
                    </Link>

                    <a href="/#about" class="hover:text-orange-500">
                        About
                    </a>

                </nav>

                <!-- Right side -->
                <div class="flex items-center gap-3">

                    <!-- Desktop Search -->
                    <div class="relative hidden sm:block">

                        <div class="flex items-center rounded-full bg-gray-100 px-4 py-2">

                            <svg
                                class="h-4 w-4 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <circle cx="11" cy="11" r="7" stroke-width="2"/>
                                <path
                                    d="m16 16 4 4"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                />
                            </svg>

                            <input
                                v-model="search"
                                type="search"
                                placeholder="Search products..."
                                class="w-36 border-0 bg-transparent py-0 pl-2 text-sm outline-none focus:ring-0"
                            />

                            <button
                                v-if="search"
                                type="button"
                                @click="clearSearch"
                                class="ml-2 text-gray-400"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- Search dropdown -->
                        <div
                            v-if="search.trim()"
                            class="absolute right-0 top-full z-[100] mt-2 w-80 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl"
                        >

                            <div class="border-b border-gray-100 px-4 py-3">
                                <p class="text-sm font-semibold">
                                    Search results
                                </p>
                            </div>

                            <div v-if="searching" class="p-4 text-sm text-gray-500">
                                Searching products...
                            </div>

                            <div
                                v-else-if="searchResults.length"
                                class="max-h-80 overflow-y-auto"
                            >
                                <div
                                    v-for="product in searchResults"
                                    :key="product.id"
                                    class="border-b border-gray-100 px-4 py-3 last:border-0 hover:bg-orange-50"
                                >
                                    <p class="font-semibold text-gray-900">
                                        {{ product.name }}
                                    </p>

                                    <p class="mt-1 line-clamp-2 text-xs text-gray-500">
                                        {{ product.descript }}
                                    </p>

                                    <p class="mt-1 text-sm font-bold text-orange-500">
                                        {{ product.price }}
                                    </p>
                                </div>
                            </div>

                            <div v-else class="p-4 text-sm text-gray-500">
                                No products found.
                            </div>

                        </div>
                    </div>

                    <!-- Account -->
                    <template v-if="authUser">

                        <Link
                            v-if="customDashboard"
                            :href="route('shop')"
                            class="hidden text-sm font-medium hover:text-orange-500 lg:block"
                        >
                            Dashboard
                        </Link>

                        <Link
                            v-if="memberItems"
                            :href="route('shop')"
                            class="hidden text-sm font-medium hover:text-orange-500 lg:block"
                        >
                            Member Items
                        </Link>

                        <Link
                            v-if="backendDashboard"
                            :href="route('dashboard')"
                            class="hidden text-sm font-medium hover:text-orange-500 lg:block"
                        >
                            Admin
                        </Link>

                    </template>

                    <!-- Guest -->
                    <template v-else-if="canLogin">

                        <Link
                            :href="route('login')"
                            class="hidden text-sm font-semibold hover:text-orange-500 sm:block"
                        >
                            Login
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="hidden rounded-full bg-gray-900 px-5 py-2 text-sm font-semibold text-white transition hover:bg-orange-500 sm:block"
                        >
                            Register
                        </Link>

                    </template>

                    <!-- Cart -->
                    <button
                        type="button"
                        class="relative rounded-full bg-gray-100 p-2.5 transition hover:bg-orange-100"
                        aria-label="Shopping cart"
                        @click="scrollToCart"
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M3 3h2l2.5 12h10.8l2.2-8H6"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                            <circle cx="9" cy="19" r="1.3" stroke-width="1.8"/>
                            <circle cx="17" cy="19" r="1.3" stroke-width="1.8"/>
                        </svg>

                        <span
                            v-if="cartCount"
                            class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-orange-500 px-1 text-xs font-bold text-white"
                        >
                            {{ cartCount }}
                        </span>

                    </button>

                </div>
            </div>

            <!-- Mobile Search -->
            <div class="px-5 pb-3 sm:hidden">

                <div class="flex items-center rounded-full border border-gray-200 bg-gray-50 px-4">

                    <input
                        v-model="search"
                        type="search"
                        placeholder="Search products..."
                        class="w-full border-0 bg-transparent text-sm focus:ring-0"
                    />

                    <button
                        v-if="search"
                        type="button"
                        @click="clearSearch"
                        class="text-gray-400 hover:text-gray-900"
                    >
                        ✕
                    </button>

                </div>

                <!-- Mobile results -->
                <div
                    v-if="search.trim()"
                    class="absolute left-5 right-5 z-[100] mt-2 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl sm:hidden"
                >

                    <div v-if="searching" class="p-4 text-sm text-gray-500">
                        Searching products...
                    </div>

                    <div
                        v-else-if="searchResults.length"
                        class="max-h-72 overflow-y-auto"
                    >
                        <div
                            v-for="product in searchResults"
                            :key="product.id"
                            class="border-b border-gray-100 p-4 last:border-0"
                        >
                            <p class="font-semibold">{{ product.name }}</p>
                            <p class="text-xs text-gray-500">{{ product.descript }}</p>
                            <p class="mt-1 text-sm font-bold text-orange-500">
                                {{ product.price }}
                            </p>
                        </div>
                    </div>

                    <div v-else class="p-4 text-sm text-gray-500">
                        No products found.
                    </div>

                </div>
            </div>
        </header>

        <!-- Page content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-black px-5 py-10 text-white">

            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 md:flex-row">

                <Link
                    :href="route('shop')"
                    class="text-2xl font-black"
                >
                    YOUR<span class="text-orange-500">SHOP.</span>
                </Link>

                <div class="flex flex-wrap justify-center gap-6 text-sm text-gray-400">

                    <Link
                        :href="route('shop.produkt.index')"
                        class="hover:text-white"
                    >
                        Shop
                    </Link>

                    <a href="/#about" class="hover:text-white">
                        About Us
                    </a>

                    <Link
                        v-if="canLogin"
                        :href="route('login')"
                        class="hover:text-white"
                    >
                        Login
                    </Link>

                </div>

                <p class="text-xs text-gray-500">
                    © {{ new Date().getFullYear() }} YourShop. All rights reserved.
                </p>

            </div>
        </footer>

    </div>
</template>
