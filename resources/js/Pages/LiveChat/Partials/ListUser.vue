<script setup>
import { ref } from 'vue';

const emit = defineEmits(['sendId']);

// Define the prop to receive the orderList
const props = defineProps({
  users: Array,
});

const userSelected = ref(null);

//onclick event sendt id to parent
const sendId = (id) => {
    userSelected.value = id;
    //emit the id to the parent
    emit('sendId', id)
}



</script>


<template>


    <h3>User</h3>

    <div class="flex flex-col gap-4">

        <div v-for="user in users" @click="sendId(user.id)" :class="{ 'bg-blue-500 text-white': user.id === userSelected }" class="cursor-pointer">

            <div class="text-lg">{{ user.name }}</div>
            <div class="text-sm">{{ user.email }}</div>


        </div>

    </div>
</template>
