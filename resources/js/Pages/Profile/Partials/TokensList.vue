<script setup>
import { ref } from 'vue';
import FormatDato from '@/FunScript/FormatDato.js'
import DangerButton from '@/Components/DangerButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
    tokens: Array,
});

const makeToken =useForm({
    name:""
})


const submit = () =>{


}

const deleteToken = (id) => {

    let deleteTokenFrom = useForm({
        id : id
    });

    deleteTokenFrom.delete(route('profile.deleteAToken'), {
        preserveScroll: true,
        onSuccess: () => deleteTokenFrom.reset(),
    });


}



</script>




<template>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Token
            </h2>
        </header>


        <div class="grid grid-cols-6 gap-4 mt-5">

            <div>Name</div>
            <div>Last used</div>
            <div>Expires at</div>
            <div>Created at</div>
            <div>Updated at</div>
            <div>Delete</div>

            <template v-for="token in tokens">

                <div>{{token.name}}</div>
                <div>{{FormatDato(token.last_used_at)}}</div>
                <div>{{FormatDato(token.expires_at)}}</div>
                <div>{{FormatDato(token.created_at)}}</div>
                <div>{{FormatDato(token.updated_at)}}</div>
                <div><DangerButton @click="deleteToken(token.id)">
                        Delete Token</DangerButton></div>

            </template>
        </div>




        <div class="mt-5 w-52">

            <form @submit.prevent="submit">

                <div>
                    <InputLabel for="name" value="name" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="makeToken.name"
                        required
                    />
                    <InputError class="mt-2" :message="makeToken.errors.name" />
                </div>

                <div class="mt-4">
                    <PrimaryButton
                        class=""
                        :class="{ 'opacity-25': makeToken.processing }"
                        :disabled="makeToken.processing"
                    >
                        Confirm
                    </PrimaryButton>
                </div>

            </form>

        </div>






</template>