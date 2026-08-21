<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Label from '@/Components/Label.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import FormatDato from '@/help/FormatDato.js'
import PrimaryButton from '@/Components/PrimaryButton.vue';


const props = defineProps({
    orderList: Object,
    statusList: Array,
    filters: Object,
});

const goToPage = (url) => {
    if (!url) return;

    router.get(url, {}, {
        preserveState: true,
        preserveScroll: true,
    });
};

const search = (event) => {
    router.get(
        route('order.index'),
        {
            search: event.target.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const status = (event) => {
    router.get(
        route('order.index'),
        {
            status: event.target.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const sortBy = (column) => {
    let direction = 'asc';

    if (props.filters?.sort === column) {
        direction = props.filters.direction === 'asc' ? 'desc' : 'asc';
    }

    router.get(
        route('order.index'),
        {
            search: props.filters?.search,
            sort: column,
            direction: direction,
            per_page: props.filters?.per_page ?? 10,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const MakeNewOrder = () =>{
    router.get(
        route('order.create')
    );
}
</script>

<template>
    <Head title="Orders" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Orders
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-5">

                    <div class="flex justify-center mb-6">
                        <Label class="text-xl">Orders</Label>
                    </div>

                    <!-- Search -->
                    <div class="mb-4 flex">
                        <input
                            type="text"
                            placeholder="Search name, email or phone..."
                            :value="filters?.search ?? ''"
                            @input="search"
                            class="border-gray-300 rounded-md shadow-sm w-full max-w-md"
                        />

                        <div class="ml-5 border-gray-300 rounded-md shadow-sm w-full max-w-md">
                            <Label>Status</Label>
                            <select v-model="filters.status" @change="status" class="border rounded px-2 py-1 w-full">
                                <option key="all" value="all" selected>All</option>
                                <option v-for="status in statusList" :key="status.Id" :value="status.Id">{{ status.Name }}</option>
                            </select>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <PrimaryButton
                                class="ms-4"
                                @click="MakeNewOrder"
                            >
                                Make new order
                            </PrimaryButton>
                        </div>

                    </div>



                    <!-- Table -->
                    <div class="overflow-x-auto">

                        <table class="w-full border-collapse">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-2 border text-center cursor-pointer"
                                        @click="sortBy('name')"
                                    >
                                        Name
                                        <span v-if="filters?.sort === 'name'">
                                            {{ filters.direction === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>

                                    <th
                                        class="px-4 py-2 border text-center cursor-pointer"
                                        @click="sortBy('email')"
                                    >
                                        Email
                                        <span v-if="filters?.sort === 'email'">
                                            {{ filters.direction === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>



                                    <th
                                        class="px-4 py-2 border text-center cursor-pointer"
                                        @click="sortBy('phone')"
                                    >
                                        Phone
                                        <span v-if="filters?.sort === 'phone'">
                                            {{ filters.direction === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>

                                    <th class="px-4 py-2 border text-center">
                                        status
                                    </th>


                                    <th
                                        class="px-4 py-2 border text-center cursor-pointer"
                                        @click="sortBy('total_price')"
                                    >
                                        Total price
                                        <span v-if="filters?.sort === 'total_price'">
                                            {{ filters.direction === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>

                                    <th
                                        class="px-4 py-2 border text-center cursor-pointer"
                                        @click="sortBy('updated_at')"
                                    >
                                        Last updated
                                        <span v-if="filters?.sort === 'updated_at'">
                                            {{ filters.direction === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>

                                    <th class="px-4 py-2 border text-center">
                                        Show
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="item in orderList.data"
                                    :key="item.uuid"
                                >
                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.name }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.email }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.phone }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.status }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.total_price }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ FormatDato(item.updated_at) }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <a
                                            :href="route('order.show', {
                                                order: item.uuid
                                            })"
                                            class="text-blue-600 hover:underline"
                                        >
                                            Show order
                                        </a>
                                    </td>
                                </tr>

                                <tr v-if="orderList.data.length === 0">
                                    <td
                                        colspan="7"
                                        class="px-4 py-6 border text-center"
                                    >
                                        No orders found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-6">
                        <div class="flex gap-1">
                            <button
                                v-for="link in orderList.links"
                                :key="link.label"
                                @click="goToPage(link.url)"
                                :disabled="!link.url"
                                v-html="link.label"
                                class="px-3 py-2 border rounded"
                                :class="{
                                    'bg-gray-200': link.active,
                                    'opacity-50 cursor-not-allowed': !link.url
                                }"
                            />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
