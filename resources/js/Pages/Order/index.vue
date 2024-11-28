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

    form.Produkts = form.Produkt.filter(x => x.id !== id);


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
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="overflow-x-auto flex justify-center">

                            <table>
                                <tr>
                                    <th class="px-4 py-2 border text-center">id</th>
                                    <th class="px-4 py-2 border text-center">total price</th>
                                    <th class="px-4 py-2 border text-center">Delete</th>
                                 
                                </tr>

                                <tr v-for="itme in orderList">
                                    <td class="px-4 py-2 border text-center">{{itme.id}}</td>
                                    <td class="px-4 py-2 border text-center">{{itme.total_price}}</td>
                                    <td @click="deleteFromOrder(itme.id)" class="px-4 py-2 border text-center">Delete</td>
                                   

                                </tr>
                                    
                            </table>



                    </div>

                   


                    
                </div>

                <div
                    class="bg-white shadow-sm sm:rounded-lg mt-5 h-96"
                >
                    
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
                                    <th class="px-4 py-2 border text-center">name</th>
                                    <th class="px-4 py-2 border text-center">price</th>
                                    <th class="px-4 py-2 border text-center">Delete</th>
                                </tr>

                                <tr v-for="itme in form.Produkts">
                                    <td class="px-4 py-2 border text-center">{{itme.name}}</td>
                                    <td class="px-4 py-2 border text-center">{{itme.prise}}</td>
                                    <td @click="deleteFromprodukt(itme.id)" class="px-4 py-2 border text-center">Delete</td>
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
