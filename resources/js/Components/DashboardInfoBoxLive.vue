<script setup>
import { watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { computed, onMounted, onUnmounted, ref,nextTick  } from 'vue';
import axios from 'axios';
import Broadcaster from '@/help/Broadcaster';


const props = defineProps({

});

const messagesContainer = ref(null);
const messages = ref([]);

const broadcaster = new Broadcaster()
const channelName = ref(null);

watch(messages, async () => {
    await nextTick();
    scrollToBottom();
}, { deep: true });


const scrollToBottom = async () => {
    await nextTick();
    if (messagesContainer.value) {

        messagesContainer.value.scrollTop =
            messagesContainer.value.scrollHeight;

    }
};

const echoFun = () =>{


    if (channelName.value) {
        broadcaster.leave(channelName.value)
    }

    channelName.value = `orders.all`;

    const fun = (response) => {
        messages.value.push(response);
    }

    broadcaster.privateChannel( channelName.value ,".OrderInfo",fun)
}

echoFun();
</script>

<template>
<div class="h-[45rem] flex flex-col">
    <div class="HoldMessage flex-grow overflow-y-auto p-5 ">
        <div class="border border-gray-200 h-full w-full">

            <div ref="messagesContainer" class="p-4 overflow-y-auto h-[35rem]">

                <div
                    v-for="message in messages"
                    :key="message.id"
                    class="flex items-center mb-2"
                >

                    <div class="p-2 mr-auto bg-gray-200 rounded-lg">
                        <a
                            :href="route('order.show', {
                                order: message.id
                            })"
                            class="text-blue-600 hover:underline"
                        >{{ message.text }} {{ message.status }} {{ message.id }}</a>
                    </div>


                </div>


            </div>

        </div>

    </div>
</div>
</template>

<style scoped>
.chat-container {
    height: 100%;
}
</style>
