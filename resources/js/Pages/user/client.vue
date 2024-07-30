<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import FileUpload from '../components/fileupload.vue'
import { router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const visible = ref(false)
const payment = ref(false)
const form = useForm({
    file: {}
})
defineOptions({layout: Layout})

const submit = () => {
    router.get('/users/create-client')
}
</script>

<template>
    <Header @submit="submit" :title="'Clients Page'"  :btnTxt="'Create Client'" />
    <Dialog v-model:visible="payment" modal header="Make Payment" :style="{ width: '50rem' }">
        <span class="text-surface-500 dark:text-surface-400 block mb-8">Please input invoice # number and attach the actual invoice</span>
        <div class="flex items-center gap-4 mb-4">
            <label for="username" class="font-semibold w-24">Amount</label>
            <InputText class="w-full rounded-md"/>
           
        </div>
        <div class="flex items-center gap-4 mb-4">
            <label for="username" class="font-semibold w-24">Invoice Number</label>
            <InputText class="w-full rounded-md"/>
           
        </div>
        <FileUpload :file="form.file" />
        <div class="flex justify-end gap-2">
            <Button type="button" label="Save" @click="submitPayment"></Button>
        </div>
    </Dialog>
    <Dialog v-model:visible="visible" modal header="Payment History" :style="{ width: '50rem' }">
        <v-table>
            <thead>
            <tr>
                <th class="text-left">
                Amount
                </th>
                <th class="text-left">
                Mode Of Payment
                </th>
                <th class="text-left">
                Payment Date
                </th>
                <th class="text-left">
                Processed By
                </th>
                <th class="text-left">
                Invoice Number
                </th>

            </tr>
            </thead>
            <tbody>
            <tr >
                <td>3,000</td>
                <td>G-cash</td>
                <td>2024-09-09</td>
                <td>Staff Name</td>
                <td>123455667</td>
            
            </tr>
            <tr >
                <td>3,000</td>
                <td>Over the Counter</td>
                <td>2024-10-09</td>
                <td>Staff Name 2</td>
                <td>12345asd7</td>
            
            </tr>
            </tbody>
        </v-table>
    </Dialog>
    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
        <v-table class="mt-4">
            <thead>
            <tr>
                <th class="text-left">
                    Name
                </th>
                <th class="text-left">
                    Email
                </th>
                <th class="text-left">
                    Phone Number
                </th>
                <th class="text-left">
                    Gross Monthly Income
                </th>
                <th class="text-left">
                    Actions
                </th>
            </tr>
            </thead>
            <tbody>
            <tr >
                <td>Dummy Name</td>
                <td>dummy@email.com</td>
                <td>1234567891</td>
                <td>20,000</td>
                <td>
                <div class="flex content-between items-center">
                
                        <button  v-tooltip.top="'Edit Clients Record'" type="button" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <v-icon name="fa-edit"></v-icon>
                        </button>
                    
                        <button  v-tooltip.top="'Delete Clients Record'" type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            <v-icon name="fa-trash"></v-icon>
                        </button>
                        <button   v-tooltip.top="'View Clients Profile'" type="button" class="text-white bg-sky-700 hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-sky-600 dark:hover:bg-sky-700 dark:focus:ring-sky-800">
                            <v-icon name="fa-eye"></v-icon>
                        </button>
                        <button @click="visible = true" v-tooltip.top="'View Clients Payment History'" type="button" class="text-white bg-amber-700 hover:bg-amber-800 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-amber-600 dark:hover:bg-amber-700 dark:focus:ring-amber-800">
                            <v-icon name="fa-clipboard"></v-icon>
                        </button>
                        <button @click="payment = true" v-tooltip.top="'Make Payment'" type="button" class="text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                            <v-icon name="fa-money-bill"></v-icon>
                        </button>
                    
                </div>
                    
                </td>
            </tr>
            
            </tbody>
    </v-table>
    </div>
</template>