<script setup>
import { watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { computed, onMounted, onUnmounted, ref } from 'vue';
import axios from 'axios';


const props = defineProps({
  user_id: Number, 
  currentuser_id: Number
});

const user_id = ref(props.user_id)

const TextToSend = ref('')
const messages = ref([]);

watch(() => props.user_id, (newVal, oldVal) => {
    user_id.value = newVal
    getmessages();
    echoFun();
})


const sendMessage = () => {
    axios.post(`/livechat/${user_id.value}`, {
        message: TextToSend.value
      })
      .then(response => {
        console.log('Message sent:', response.data);
        TextToSend.value = '';
        messages.value.push(response.data);
      })
      .catch(error => {
        console.error('Error sending message:', error);
      });
}

const getmessages = () => {
    axios.get(`/livechat/${user_id.value}`)
      .then(response => {
        console.log('Messages:', response.data);
        messages.value = response.data;
      })
      .catch(error => {
        console.error('Error getting messages:', error);
      });
}

const echoFun = () =>{

    Echo.private(`chat.${props.currentuser_id}`)
        .listen("MessageSent", (response) => {
            console.log(response)
            messages.value.push(response.message);

            console.log(messages.value)
        })
}


</script>

<template>
<div class="chat-container h-full flex flex-col">
    <div class="HoldMessage flex-grow overflow-y-auto p-5 ">
        <div class="border border-gray-200 h-full w-full">
            
            <div ref="messagesContainer" class="p-4 overflow-y-auto h-[35rem]">

                <div
                    v-for="message in messages"
                    :key="message.id"
                    class="flex items-center mb-2"
                >
                    <div
                        v-if="message.sender_id === currentuser_id"
                        class="p-2 ml-auto text-white bg-blue-500 rounded-lg"
                    >
                        {{ message.text }}
                    </div>
                    <div v-else class="p-2 mr-auto bg-gray-200 rounded-lg">
                        {{ message.text }}
                    </div>
            
            
                </div>


            </div>

        </div>
        
    </div>

    <div class="newMessage flex-shrink-0 p-4">
        <input type="text" v-model="TextToSend" class="w-4/5 inline-flex items-center rounded-md px-4 py-2 text-xs font-semibold tracking-widest transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 mr-5" placeholder="Type your message here">
        <PrimaryButton  @click="sendMessage">Send</PrimaryButton>
    </div>
</div>
</template>

<style scoped>
.chat-container {
    height: 100%;
}
</style>