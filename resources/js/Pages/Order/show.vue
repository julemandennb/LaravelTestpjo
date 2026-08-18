<script setup>
import { ref,computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Head,useForm,router } from '@inertiajs/vue3';

import FormatDato from '@/help/FormatDato.js'

import Label from '@/Components/Label.vue'


// Define the prop to receive the orderList
const props = defineProps({
  order: Object,
  produktList: Array, // Make sure it's passed as an object
  statusList: Array
});

console.log(props.order.products);

const AddProd = ref(0);


const form = useForm({
    products: props.order.products.map(product => ({
    ...product
    })),

    name : props.order.name,
    email:props.order.email,
    phone:props.order.phone,
    address: props.order.address,
    postNr: props.order.postNr,
    status: props.order.status
});

const IsDone = computed(() =>
    ['shipped', 'completed', 'cancelled'].includes(props.order.status)
);

const deleteFromprodukt = (id,produktID) =>{

    let idSearch = (id == null)

    let formProducts = idSearch ? form.products.find(x => x.produktID == produktID) :  form.products.find(x => x.id == id);

    if(formProducts.quantity == 1)
        form.products =  idSearch ? form.products.filter(x => x.produktID !== produktID) : form.products.filter(x => x.id !== id);
    else
        formProducts.quantity --

}


const makeFormProdukt = (produkt) =>
{
    return {
        name: produkt.name,
        price:produkt.price,
        quantity:  1,
        produktID: produkt.id
    }
}

const selectchange = () =>
{

    let produkt = props.produktList.find(x => x.id == AddProd.value)
    let formProducts = form.products.find(x => x.produktID == AddProd.value);

    if(!formProducts)
        form.products.push(makeFormProdukt(produkt));
    else
        formProducts.quantity++

    AddProd.value = 0

}


const updata = () => {
    if (!IsDone) return;

    form.put(route('order.update', { order: props.order.id }));
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

                    <div class="overflow-x-auto flex justify-center  mt-5"><Label class="text-xl">products</Label></div>

                     <div class="overflow-x-auto flex justify-center"><Label class="text-xl">add new product</Label></div>

                        <div class="overflow-x-auto flex justify-center mb-5">

                            <select v-model="AddProd" @change="selectchange">
                                <option value="0" disabled selected hidden>add a produkt</option>
                                <option v-for="produkt in produktList" :value="produkt.id">{{ produkt.name }} {{ produkt.price }}.KR</option>
                            </select>
                        </div>


                    <div class="overflow-x-auto flex justify-center">

                        <table>
                            <tr>
                                <th class="px-4 py-2 border text-center"><Label>name</Label></th>
                                <th class="px-4 py-2 border text-center"><Label>price</Label></th>
                                <th class="px-4 py-2 border text-center"><Label>quantity</Label></th>
                                <th class="px-4 py-2 border text-center"><Label>Delete</Label></th>
                            </tr>

                            <tr v-for="itme in form.products">
                                <td class="px-4 py-2 border text-center"><Label>{{itme.name}}</Label></td>
                                <td class="px-4 py-2 border text-center"><Label>{{itme.price }}</Label></td>
                                <td class="px-4 py-2 border text-center"><Label>{{itme.quantity}}</Label></td>
                                <td @click="deleteFromprodukt(itme.id,itme.produktID)" class="px-4 py-2 border text-center"><Label>Delete</Label></td>
                            </tr>

                        </table>

                    </div>

                     <div class="overflow-x-auto flex justify-center mt-5"  >
                        <PrimaryButton :disabled="IsDone"  @click="updata">opdate</PrimaryButton>
                    </div>


                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
