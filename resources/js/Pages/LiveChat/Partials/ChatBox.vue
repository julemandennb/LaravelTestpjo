<script setup>
import { watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { computed, onMounted, onUnmounted, ref } from 'vue';
import axios from 'axios';
import Echo from 'laravel-echo';


const props = defineProps({
  user_id: Number, 
  currentuser_id: Number
});

const user_id = ref(props.user_id)

const TextToSend = ref('')
const messages = ref([]);

watch(() => props.user_id, (newVal, oldVal) => {
    console.log(newVal)
    console.log(oldVal)
    user_id.value = newVal
    echoFun();
})


const sendMessage = () => {
    axios.post(`/livechat/${user_id.value}`, {
        message: TextToSend.value
      })
      .then(response => {
        console.log('Message sent:', response.data);
      })
      .catch(error => {
        console.error('Error sending message:', error);
      });
}


const echoFun = () =>{


    Echo.private(`chat.${props.currentuser_id}`)
        .listen("MessageSent", (response) => {
            messages.value.push(response.message);
        })
       /* .listenForWhisper("typing", (response) => {
            isFriendTyping.value = response.userID === user_id.value;
 
            if (isFriendTypingTimer.value) {
                clearTimeout(isFriendTypingTimer.value);
            }
 
            isFriendTypingTimer.value = setTimeout(() => {
                isFriendTyping.value = false;
            }, 1000);
        });*/



}





</script>

<template>
<div class="chat-container h-full flex flex-col">
    <div class="HoldMessage flex-grow overflow-y-auto p-5 ">
        <div class="border border-gray-200 h-full w-full">
            asasas
        </div>
        
    </div>

    <div class="newMessage flex-shrink-0 p-4">
        <input type="text" v-model="TextToSend" class="w-4/5 inline-flex items-center rounded-md px-4 py-2 text-xs font-semibold uppercase tracking-widest transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 mr-5" placeholder="Type your message here">
        <PrimaryButton  @click="sendMessage">Send</PrimaryButton>
    </div>
</div>
</template>

<style scoped>
.chat-container {
    height: 100%;
}
</style>