<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Head,useForm } from '@inertiajs/vue3';

import FormatDato from '@/help/FormatDato.js'

import Label from '@/Components/Label.vue'

// Define the prop to receive the orderList
const props = defineProps({
  order: Object,
  produktList: Array, // Make sure it's passed as an object
  statusList: Array
});

console.log(props.order)

const AddProd = ref(0);
const IsDone = (props.order.status == "shipped" || props.order.status == "completed" || props.order.status == "cancelled" );

const form = useForm({
    Produkts: props.order.products,

    name : props.order.name,
    email:props.order.email,
    phone:props.order.phone,
    address: props.order.address,
    postNr: props.order.postNr,
    status: props.order.status
});

const deleteFromprodukt = (id) =>{

    form.Produkts = form.Produkts.filter(x => x.id !== id);

    updata();


}

const selectchange = () =>
{

    let produkt = props.produktList.find(x => x.id == AddProd.value)

    form.Produkts.push(produkt);

    AddProd.value = 0

    updata();
}


const updata = () => {
    if(!IsDone)
    form.put(route('order.update',{ order: props.order.id }), {
        onSuccess: () => form.reset(),
    });
};




</script>

<template>
    <Head title="order" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
            order
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-5"
                >

                <div class="overflow-x-auto flex justify-center"><Label class="text-xl">Order info {{ order.id }}</Label></div>

                    <div class="overflow-x-auto flex justify-center mb-5">

                        <div class="grid grid-cols-2 gap-4">
                            <div>created_at</div>
                            <div>{{ FormatDato(order.created_at) }}</div>

                            <div v-if="order.created_at != order.updated_at">updated_at</div>
                            <div v-if="order.created_at != order.updated_at">{{ FormatDato(order.updated_at) }}</div>

                            <div>make</div>
                            <div>{{ order.user.name }}</div>


                            <div>total price</div>
                            <div>{{ order.total_price }}</div>

                        </div>


                    </div>

                    <div class="overflow-x-auto flex justify-center">
                        <div class="grid grid-cols-2 gap-3 mb-5 max-w-md">
                            <div>
                                <Label>Name</Label>
                                <input v-model="form.name" class="border rounded px-2 py-1 w-full" :disabled="IsDone"/>
                                <div v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</div>
                            </div>

                            <div>
                                <Label>Email</Label>
                                <input v-model="form.email" type="email" class="border rounded px-2 py-1 w-full" :disabled="IsDone"/>
                                <div v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</div>
                            </div>

                            <div>
                                <Label>Phone</Label>
                                <input v-model="form.phone" class="border rounded px-2 py-1 w-full" :disabled="IsDone"/>
                                <div v-if="form.errors.phone" class="text-red-600 text-sm">{{ form.errors.phone }}</div>
                            </div>

                            <div>
                                <Label>Address</Label>
                                <input v-model="form.address" class="border rounded px-2 py-1 w-full" :disabled="IsDone"/>
                                <div v-if="form.errors.address" class="text-red-600 text-sm">{{ form.errors.address }}</div>
                            </div>

                            <div>
                                <Label>Post Nr</Label>
                                <input v-model="form.postNr" class="border rounded px-2 py-1 w-full" :disabled="IsDone"/>
                                <div v-if="form.errors.postNr" class="text-red-600 text-sm">{{ form.errors.postNr }}</div>
                            </div>

                            <div>
                                <Label>Status</Label>
                                <select v-model="form.status" class="border rounded px-2 py-1 w-full" :disabled="IsDone">
                                    <option v-for="status in statusList" :key="status.Id" :value="status.Id">{{ status.Name }}</option>
                                </select>
                                <div v-if="form.errors.status" class="text-red-600 text-sm">{{ form.errors.status }}</div>
                            </div>



                        </div>

                    </div>

                    <div class="overflow-x-auto flex justify-center"  >
                        <PrimaryButton :disabled="IsDone"  @click="updata">opdate</PrimaryButton>
                    </div>


                    <div class="overflow-x-auto flex justify-center  mt-5"><Label class="text-xl">product</Label></div>

                    <div class="overflow-x-auto flex justify-center">

                        <table>
                            <tr>
                                <th class="px-4 py-2 border text-center"><Label>name</Label></th>
                                <th class="px-4 py-2 border text-center"><Label>price</Label></th>
                                <th class="px-4 py-2 border text-center"><Label>quantity</Label></th>
                                <th class="px-4 py-2 border text-center"><Label>Delete</Label></th>
                            </tr>

                            <tr v-for="itme in form.Produkts">
                                <td class="px-4 py-2 border text-center"><Label>{{itme.name}}</Label></td>
                                <td class="px-4 py-2 border text-center"><Label>{{itme.pivot?.price ?? itme.price }}</Label></td>
                                <td class="px-4 py-2 border text-center"><Label>{{itme.pivot?.quantity ?? 1}}</Label></td>
                                <td @click="deleteFromprodukt(itme.id)" class="px-4 py-2 border text-center"><Label>Delete</Label></td>
                            </tr>

                        </table>

                    </div>

                        <div class="overflow-x-auto flex justify-center"><Label class="text-xl">add new product</Label></div>

                        <div class="overflow-x-auto flex justify-center">

                            <select v-model="AddProd" @change="selectchange">
                                <option value="0" disabled selected hidden>add a produkt</option>
                                <option v-for="produkt in produktList" :value="produkt.id">{{ produkt.name }} {{ produkt.price }}.KR</option>
                            </select>
                        </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
