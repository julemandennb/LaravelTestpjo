<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Head } from '@inertiajs/vue3';
import ListUser from './Partials/ListUser.vue';
import ChatBox from './Partials/ChatBox.vue';

// Define the prop to receive the orderList
const props = defineProps({
  users: Array, // Make sure it's passed as an object
  currentuser_id: Number
});

const UserID = ref(0);


</script>


<template>
    <Head title="LiveChat" />

    <AuthenticatedLayout>

        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                LiveChat
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg flex"
                >
                    <div class="w-1/4"><ListUser :users="users" @send-id="(id)=>{UserID = id}" /></div>
                    
                    <div class="w-3/4"><ChatBox :user_id="UserID" :currentuser_id="currentuser_id"/></div>
                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>



</template>