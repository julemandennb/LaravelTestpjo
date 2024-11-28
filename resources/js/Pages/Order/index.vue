<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Head,useForm } from '@inertiajs/vue3';

import Label from '@/Components/Label.vue'

// Define the prop to receive the orderList
const props = defineProps({
  orderList: Array, // Make sure it's passed as an object
  produktList: Array
});


const form = useForm({
    Produkts: [],
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

    form.Produkts.push(produkt);

    AddProd.value = 0
}

const deleteFromprodukt = (id) =>{

    form.Produkts = form.Produkts.filter(x => x.id !== id);


}

const deleteFromOrder = (id) =>{

    let OrderFrom = useForm({});

    // Pass the order ID in the route
    OrderFrom.delete(route('order.delete', { order: id }), {
        onSuccess: () => OrderFrom.reset(),
    });

}


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

                <div class="overflow-x-auto flex justify-center"><Label class="text-xl">Orders</Label></div>

                    <div class="overflow-x-auto flex justify-center">

                            <table>
                                <tr>
                                    <th class="px-4 py-2 border text-center"><Label>id</Label></th>
                                    <th class="px-4 py-2 border text-center"><Label>total price</Label></th>
                                    <th class="px-4 py-2 border text-center"><Label>total produkt</Label></th>
                                    <th class="px-4 py-2 border text-center"><Label>show</Label></th>
                                    <th class="px-4 py-2 border text-center"><Label>Delete</Label></th>
                                 
                                </tr>

                                <tr v-for="itme in orderList">
                                    <td class="px-4 py-2 border text-center"><Label>{{itme.id}}</Label></td>
                                    <td class="px-4 py-2 border text-center"><Label>{{itme.total_price}}</Label></td>
                                    <td class="px-4 py-2 border text-center"><Label>{{itme.products.length}}</Label></td>
                                    <td class="px-4 py-2 border text-center"><Label> <a :href="route('order.show',{ order: itme.id })">Show order</a></Label></td>
                                    <td @click="deleteFromOrder(itme.id)" class="px-4 py-2 border text-center"><Label>Delete</Label></td>
                                   

                                </tr>
                                    
                            </table>



                    </div>
                    
                </div>

                <div
                    class="bg-white shadow-sm sm:rounded-lg mt-5 h-96 p-5"
                >
                   
                <div class="overflow-x-auto flex justify-center"><Label class="text-xl">make Order</Label></div>

                <div class="overflow-x-auto flex justify-center">
                    
                    <select v-model="AddProd" @change="selectchange">
                        <option value="0" disabled selected hidden>add a produkt</option>
                        <option v-for="produkt in produktList" :value="produkt.id">{{ produkt.name }}</option>
                    </select>
                </div>
                        
            
                <div class="overflow-x-auto flex justify-center">
                    <form @submit.prevent="submit">

                        <table>
                                <tr>
                                    <th class="px-4 py-2 border text-center"><Label>name</Label></th>
                                    <th class="px-4 py-2 border text-center"><Label>price</Label></th>
                                    <th class="px-4 py-2 border text-center"><Label>Delete</Label></th>
                                </tr>

                                <tr v-for="itme in form.Produkts">
                                    <td class="px-4 py-2 border text-center"><Label>{{itme.name}}</Label></td>
                                    <td class="px-4 py-2 border text-center"><Label>{{itme.prise}}</Label></td>
                                    <td @click="deleteFromprodukt(itme.id)" class="px-4 py-2 border text-center"><Label>Delete</Label></td>
                                </tr>
                                    
                            </table>
                        

                        <PrimaryButton>Make</PrimaryButton>
                    </form>
                </div>



                
            
            
                </div>


            </div>
        </div>
    </AuthenticatedLayout>
</template>
