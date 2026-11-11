
<script setup>
import { ref, watch, onBeforeUnmount } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import ShopLayout from '@/Layouts/ShopLayout.vue';
import { formatPrice } from '@/help/FormatFun';

const page = usePage();

const props = defineProps({
    produktList: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
});

const search = ref(props.filters.search ?? '');
const sort = ref(props.filters.sort ?? 'id');
const direction = ref(props.filters.direction ?? 'desc');
const perPage = ref(props.filters.per_page ?? 10);

let searchTimeout = null;

function applyFilters() {
    router.get(
        route('shop.produkt.index'),
        {
            search: search.value,
            sort: sort.value,
            direction: direction.value,
            per_page: perPage.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}

// Search debounce
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 400);
});

// Apply sorting and pagination filters
watch([sort, direction, perPage], applyFilters);

onBeforeUnmount(() => {
    clearTimeout(searchTimeout);
});

</script>

<template>
    <Head title="Products" />

    <ShopLayout
        :can-login="page.props.canLogin"
        :can-register="page.props.canRegister"
        :custom-dashboard="page.props.customDashboard"
        :member-items="page.props.memberItems"
        :backend-dashboard="page.props.backendDashboard"
    >
        <div class="min-h-screen bg-gray-50">

            <!-- Product Content -->
            <section class="mx-auto max-w-7xl px-5 py-10 lg:px-8">

                <!-- Filters -->
                <div class="mb-8 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-4">

                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Search products
                            </label>

                            <input
                                v-model="search"
                                type="search"
                                placeholder="Search by name, description or price..."
                                class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                            />
                        </div>

                        <!-- Sort -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Sort by
                            </label>

                            <select
                                v-model="sort"
                                class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                            >
                                <option value="id">Newest</option>
                                <option value="name">Name</option>
                                <option value="price">Price</option>
                            </select>
                        </div>

                        <!-- Direction -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-gray-700">
                                Order
                            </label>

                            <select
                                v-model="direction"
                                class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                            >
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </select>
                        </div>

                    </div>

                    <!-- Per Page -->
                    <div class="mt-5 flex items-center gap-3">
                        <label class="text-sm text-gray-600">
                            Products per page:
                        </label>

                        <select
                            v-model.number="perPage"
                            class="rounded-lg border-gray-300 text-sm focus:border-orange-500 focus:ring-orange-500"
                        >
                            <option :value="10">10</option>
                            <option :value="20">20</option>
                            <option :value="50">50</option>
                            <option :value="100">100</option>
                        </select>
                    </div>

                </div>

                <!-- Results information -->
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">
                        All Products
                    </h2>

                    <p class="text-sm text-gray-500">
                        {{ produktList.total }} products found
                    </p>
                </div>

                <!-- Product Grid -->
                <div
                    v-if="produktList.data.length > 0"
                    class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                >

                    <article
                        v-for="produkt in produktList.data"
                        :key="produkt.id"
                        class="group overflow-hidden rounded-xl border border-gray-200 bg-white transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                    >

                        <!-- Product Image Placeholder -->
                        <div class="flex h-52 items-center justify-center bg-gray-100">
                            <span class="text-6xl text-gray-300">
                                📦
                            </span>
                        </div>

                        <!-- Product Information -->
                        <div class="p-5">

                            <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-orange-500">
                                Product #{{ produkt.id }}
                            </p>

                            <h3 class="text-lg font-bold text-gray-900">
                                {{ produkt.name }}
                            </h3>

                            <p class="mt-2 line-clamp-3 min-h-12 text-sm text-gray-500">
                                {{ produkt.descript }}
                            </p>

                            <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">

                                <span class="text-xl font-black text-orange-500">
                                    {{ formatPrice(produkt.price) }}
                                </span>

                                <button
                                    type="button"
                                    class="rounded-full bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-500"
                                >
                                    Details
                                </button>

                            </div>

                        </div>
                    </article>

                </div>

                <!-- Empty State -->
                <div
                    v-else
                    class="rounded-xl border border-gray-200 bg-white px-6 py-16 text-center"
                >
                    <div class="mb-4 text-5xl">
                        🔍
                    </div>

                    <h3 class="text-xl font-bold text-gray-900">
                        No products found
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Try another search term or change your filters.
                    </p>

                    <button
                        type="button"
                        @click="
                            search = '';
                            sort = 'id';
                            direction = 'desc';
                            perPage = 10;
                        "
                        class="mt-5 rounded-full bg-orange-500 px-5 py-2 text-sm font-semibold text-white hover:bg-orange-600"
                    >
                        Clear filters
                    </button>
                </div>

                <!-- Pagination -->
                <div
                    v-if="produktList.links?.length > 3"
                    class="mt-10 flex flex-wrap justify-center gap-2"
                >
                    <template
                        v-for="(link, index) in produktList.links"
                        :key="index"
                    >
                        <button
                            v-if="link.url"
                            type="button"
                            @click="router.visit(link.url, {
                                preserveScroll: true,
                                preserveState: true
                            })"
                            v-html="link.label"
                            class="rounded-lg border px-4 py-2 text-sm transition"
                            :class="link.active
                                ? 'border-orange-500 bg-orange-500 text-white'
                                : 'border-gray-200 bg-white text-gray-700 hover:bg-orange-50'"
                        />

                        <span
                            v-else
                            v-html="link.label"
                            class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-300"
                        />
                    </template>
                </div>

            </section>
        </div>
    </ShopLayout>
</template>
