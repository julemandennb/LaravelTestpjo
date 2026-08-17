<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Head,useForm } from '@inertiajs/vue3';
import Label from '@/Components/Label.vue'


const props = defineProps({
    produktList: Array,
    statusList: Array

});


const form = useForm({
    name : "",
    email:"",
    phone:"",
    address: "",
    postNr: "",
    status: props.statusList?.[0]?.Id ?? '',
    produkts: [],
});

const submit = () => {
    form.post(route('order.store'), {
        onSuccess: () => form.reset(),
    });
};

const AddProd = ref(0);

const selectchange = () =>
{

    let produkt = props.produktList.find(x => x.id == AddProd.value)

    form.produkts.push(produkt);

    AddProd.value = 0
}

const deleteFromprodukt = (id) =>{

    form.produkts = form.produkts.filter(x => x.id !== id);


}

</script>


<template>
    <Head title="create order" />
    <AuthenticatedLayout>
       <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create order
            </h2>
        </template>

              <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="bg-white shadow-sm sm:rounded-lg mt-5 h-auto p-5"
                >

                <div class="overflow-x-auto flex justify-center"><Label class="text-xl">Make new order</Label></div>




                <div class="overflow-x-auto flex justify-center">
                    <form @submit.prevent="submit">

                        <div class="grid grid-cols-2 gap-3 mb-5 max-w-md">
                            <div>
                                <Label>Name</Label>
                                <input v-model="form.name" class="border rounded px-2 py-1 w-full" />
                                <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <Label>Email</Label>
                                <input v-model="form.email" type="email" class="border rounded px-2 py-1 w-full" />
                                <div v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</div>
                            </div>

                            <div>
                                <Label>Phone</Label>
                                <input v-model="form.phone" class="border rounded px-2 py-1 w-full" />
                                <div v-if="form.errors.phone" class="text-red-600 text-sm">{{ form.errors.phone }}</div>
                            </div>

                            <div>
                                <Label>Address</Label>
                                <input v-model="form.address" class="border rounded px-2 py-1 w-full" />
                                <div v-if="form.errors.address" class="text-red-600 text-sm">{{ form.errors.address }}</div>
                            </div>

                            <div>
                                <Label>Post Nr</Label>
                                <input v-model="form.postNr" class="border rounded px-2 py-1 w-full" />
                                <div v-if="form.errors.postNr" class="text-red-600 text-sm">{{ form.errors.postNr }}</div>
                            </div>

                            <div>
                                <Label>Status</Label>
                                <select v-model="form.status" class="border rounded px-2 py-1 w-full">
                                    <option v-for="status in statusList" :key="status.Id" :value="status.Id">{{ status.Name }}</option>
                                </select>
                                <div v-if="form.errors.status" class="text-red-600 text-sm">{{ form.errors.status }}</div>
                            </div>
                        </div>

                        <div class="overflow-x-auto flex justify-center mb-5">
                            <select v-model="AddProd" @change="selectchange">
                                <option value="0" disabled selected hidden>add a produkt</option>
                                <option v-for="produkt in produktList" :value="produkt.id">{{ produkt.name }} {{ produkt.price }}.KR</option>
                            </select>
                        </div>

                        <div class="overflow-x-auto flex justify-center">
                            <table class="mb-5">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border text-center"><Label>name</Label></th>
                                        <th class="px-4 py-2 border text-center"><Label>price</Label></th>
                                        <th class="px-4 py-2 border text-center"><Label>Delete</Label></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="itme in form.produkts" :key="itme.id">
                                        <td class="px-4 py-2 border text-center"><Label>{{ itme.name }}</Label></td>
                                        <td class="px-4 py-2 border text-center"><Label>{{ itme.price }}</Label></td>
                                        <td @click="deleteFromprodukt(itme.id)" class="px-4 py-2 border text-center cursor-pointer"><Label>Delete</Label></td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>

                         <div class="overflow-x-auto flex justify-end">
                            <PrimaryButton type="submit">Make</PrimaryButton>
                        </div>
                    </form>
                </div>






                </div>


            </div>
        </div>

    </AuthenticatedLayout>
</template>
