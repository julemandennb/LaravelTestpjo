<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Label from '@/Components/Label.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import FormatDato from '@/help/FormatDato.js'
import PrimaryButton from '@/Components/PrimaryButton.vue';


const props = defineProps({
    activitys: Object,
    logNames: Array,
    filters: Object,
});

const getFilters = () => ({
    search: props.filters?.search ?? '',
    logtype: props.filters?.logtype ?? 'all',
    sort: props.filters?.sort ?? 'updated_at',
    direction: props.filters?.direction ?? 'desc',
    per_page: props.filters?.per_page ?? 10,
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
        route('activityLog.index'),
        {
            ...getFilters(),
            search: event.target.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    );
};

const filterLogName = (event) => {
    router.get(
        route('activityLog.index'),
        {
            ...getFilters(),
            logtype: event.target.value,
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
        route('activityLog.index'),
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

</script>

<template>
    <Head title="ActivityLog" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                ActivityLog
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-5">

                    <div class="flex justify-center mb-6">
                        <Label class="text-xl">ActivityLogs</Label>
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
                            <Label>logName</Label>
                            <select v-model="filters.logtype" @change="filterLogName" class="border rounded px-2 py-1 w-full">
                                <option key="all" value="all" selected>All</option>
                                <option v-for="status in logNames" :key="status.Id" :value="status.Id">{{ status.Name }}</option>
                            </select>
                        </div>

                    </div>



                    <!-- Table -->
                    <div class="overflow-x-auto">

                        <table class="w-full border-collapse">
                            <thead>
                                <tr>
                                    <th
                                        class="px-4 py-2 border text-center cursor-pointer"
                                        @click="sortBy('log_name')"
                                    >
                                        log Name
                                        <span v-if="filters?.sort === 'log_name'">
                                            {{ filters.direction === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>

                                     <th
                                        class="px-4 py-2 border text-center cursor-pointer"
                                        @click="sortBy('subject_id')"
                                    >
                                        Subject id
                                        <span v-if="filters?.sort === 'subject_id'">
                                            {{ filters.direction === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>

                                    <th
                                        class="px-4 py-2 border text-center cursor-pointer"
                                        @click="sortBy('description')"
                                    >
                                        Description
                                        <span v-if="filters?.sort === 'description'">
                                            {{ filters.direction === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>



                                    <th
                                        class="px-4 py-2 border text-center cursor-pointer"
                                        @click="sortBy('event')"
                                    >
                                        Event
                                        <span v-if="filters?.sort === 'event'">
                                            {{ filters.direction === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>

                                    <th
                                        class="px-4 py-2 border text-center cursor-pointer"
                                        @click="sortBy('causer_id')"
                                    >
                                        causer
                                        <span v-if="filters?.sort === 'causer_id'">
                                            {{ filters.direction === 'asc' ? '↑' : '↓' }}
                                        </span>
                                    </th>

                                    <th class="px-4 py-2 border text-center">
                                        Attribute changes
                                    </th>
                                    <th class="px-4 py-2 border text-center">
                                        Properties
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

                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="item in activitys.data"
                                    :key="item.id"
                                >
                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.log_name }}</Label>
                                    </td>

                                     <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.subject_id }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.description }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.event }}</Label>
                                    </td>


                                    <td class="px-4 py-2 border text-center">
                                        <Label> {{ item.causer?.name ?? 'System' }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.attribute_changes }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ item.properties }}</Label>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <Label>{{ FormatDato(item.updated_at) }}</Label>
                                    </td>
                                </tr>

                                <tr v-if="activitys.data.length === 0">
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
                                v-for="link in activitys.links"
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
