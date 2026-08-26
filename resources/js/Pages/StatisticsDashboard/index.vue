<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Label from '@/Components/Label.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import FormatDato from '@/help/FormatDato.js'
import { onMounted, onUnmounted } from 'vue';


const props = defineProps({
    totalUsers: Number,
    get5LastUser: Array,
    activeUsers: Array,

    totalCharts: Number,
    totalUnreadCharts: Number,
    totalOrder: Number,
    totalOrderToday: Number,
    totalCompletedOrder: Number,

});

let refreshInterval;

onMounted(() => {
    refreshInterval = setInterval(() => {
        router.reload({
            only: [
                'totalUsers',
                'get5LastUser',
                'activeUsers',
                'totalCharts',
                'totalUnreadCharts',
                'totalOrder',
                'totalOrderToday',
                'totalCompletedOrder',
            ],
            preserveScroll: true,
            preserveState: true,
        });
    }, 10000);
});

onUnmounted(() => {
    clearInterval(refreshInterval);
});


</script>

<template>
    <Head title="StatisticsDashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Statistics Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-5">

                    <div class="flex justify-center mb-6">
                        <Label class="text-xl">Statistics Dashboard</Label>
                    </div>

                    <div class="flex justify-center flex-col ">

                        <div class="flex justify-center gap-5 mb-6">
                            <Label class="flex-1 border-2 border-solid ">Total users : {{ totalUsers }}</Label>
                            <Label class="flex-1 border-2 border-solid ">Total charts : {{ totalCharts }}</Label>
                            <Label class="flex-1 border-2 border-solid ">Total unreadCharts : {{ totalUnreadCharts }}</Label>
                        </div>
                        <div class="flex justify-center gap-5 mb-6">
                            <Label class="flex-1 border-2 border-solid ">Total order : {{ totalOrder }}</Label>
                            <Label class="flex-1 border-2 border-solid ">Total order today : {{ totalOrderToday }}</Label>
                            <Label class="flex-1 border-2 border-solid ">Total completed order : {{ totalCompletedOrder }}</Label>
                        </div>

                        <Label class="flex-1 text-lg border-t-4">Last login user</Label>
                        <div class="grid grid-cols-3 gap-4">
                            <div>Name</div>
                            <div>Email</div>
                            <div>Last login</div>

                            <slot v-for="user in get5LastUser">

                                <div>{{user.name}}</div>
                                <div>{{user.email}}</div>
                                <div>{{FormatDato(user.last_login)}}</div>


                            </slot>

                        </div>

                        <Label class="flex-1 text-lg mt-5 border-t-4">Active users</Label>
                        <div class="grid grid-cols-5 gap-4">
                            <div>Name</div>
                            <div>Email</div>
                            <div>Orders count</div>
                            <div>Sent messages</div>
                            <div>Received messages</div>

                            <slot v-for="user in activeUsers">

                                <div>{{user.name}}</div>
                                <div>{{user.email}}</div>
                                <div>{{user.orders_count}}</div>
                                <div>{{user.sent_messages_count}}</div>
                                <div>{{user.received_messages_count}}</div>


                            </slot>

                        </div>



                    </div>



                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
