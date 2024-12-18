<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Head,useForm } from '@inertiajs/vue3';

import FormatDato from '@/FunScript/FormatDato.js'

import Label from '@/Components/Label.vue'

// Define the prop to receive the orderList
const props = defineProps({
  order: Object,
  produktList: Array // Make sure it's passed as an object
});


const AddProd = ref(0);


const form = useForm({
    Produkts: props.order.products,
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

                    <div class="overflow-x-auto flex justify-center">

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

                    <div class="overflow-x-auto flex justify-center  mt-5"><Label class="text-xl">product</Label></div>

                    <div class="overflow-x-auto flex justify-center">

                        <table>
                                <tr>
                                    <th class="px-4 py-2 border text-center"><Label>name</Label></th>
                                    <th class="px-4 py-2 border text-center"><Label>price</Label></th>
                                    <th class="px-4 py-2 border text-center"><Label>Delete</Label></th>
                                </tr>

                                <tr v-for="itme in order.products">
                                    <td class="px-4 py-2 border text-center"><Label>{{itme.name}}</Label></td>
                                    <td class="px-4 py-2 border text-center"><Label>{{itme.prise}}</Label></td>
                                    <td @click="deleteFromprodukt(itme.id)" class="px-4 py-2 border text-center"><Label>Delete</Label></td>
                                </tr>
                                    
                            </table>
   
                </div>
                    
                </div>

                <div
                    class="bg-white shadow-sm sm:rounded-lg mt-5 h-96 p-5"
                >
                   
                <div class="overflow-x-auto flex justify-center"><Label class="text-xl">add new product</Label></div>

                <div class="overflow-x-auto flex justify-center">
                    
                    <select v-model="AddProd" @change="selectchange">
                        <option value="0" disabled selected hidden>add a produkt</option>
                        <option v-for="produkt in produktList" :value="produkt.id">{{ produkt.name }}</option>
                    </select>
                </div>
                        
            
                



                
            
            
                </div>


            </div>
        </div>
    </AuthenticatedLayout>
</template>
