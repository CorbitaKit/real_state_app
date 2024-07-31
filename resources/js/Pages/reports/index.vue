<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = useForm({
    date_from: '',
    date_to: '',
    client: '',
    mode_of_payment: ''
})

const mode_of_payments = ref([
    {
        mode_of_payment: 'GCash',
    },
    {
        mode_of_payment: 'Bank Transfer',
    },
    {
        mode_of_payment: 'Over the Counter',
    }
])
defineOptions({layout: Layout})

const submit = () => {
    router.get('/users/create-client')
}
</script>

<template>
    <Header @submit="submit" :title="'Reports Page'"  :btnTxt="'Create Client'" :displayBtn="false" />

    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md flex content-between items-center">
        <div class="mb-4 m-2">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">Date From</label>
            <Calendar v-model="form.date_from" class="w-full rounded-md" style="border-radius: 7px;"/>
        </div>

        <div class="mb-4 m-2">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">Date To</label>
            <Calendar v-model="form.date_to" class="w-full rounded-md" style="border-radius: 7px;"/>
        </div>

        <div class="mb-4 m-2">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">Clients</label>
            <InputText type="text" v-model="form.client" class="block appearance-none w-full px-3 py-2 border " placeholder="... Search Client"/>        
        </div>
        <div class="mb-4 mt-3">
            <label for="region" class="block text-gray-700 font-semibold mb-2">Mode of payment</label>
            <Dropdown v-model="form.mode_of_payment" :options="mode_of_payments" filter optionLabel="mode_of_payment" placeholder="Select a mode of payment" class="w-full md:w-full border" />
        </div>
    </div>

    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md"  >
        <v-table id="printMe">
            <thead>
            <tr>
                <th class="text-left">
                Client Name
                </th>
                
                <th class="text-left">
                Mode Of Payment
                </th>
                <th class="text-left">
                Payment Date
                </th>
                <th class="text-left">
                </th>
                <th class="text-left">
                Invoice Number
                </th>
                <th class="text-left">
                Amount
                </th>

            </tr>
            </thead>
            <tbody>
            <tr >
                <td>Test Client Name</td>
                
                <td>G-cash</td>
                <td>2024-09-09</td>
                <td>Staff Name</td>
                <td>123455667</td>
                <td>3,000</td>
            
            </tr>
            <tr >
                <td>Test Client Name 2</td>

                
                <td>Over the Counter</td>
                <td>2024-10-09</td>
                <td>Staff Name 2</td>
                <td>12345asd7</td>
                <td>3,000</td>
            
            </tr>
            <tr >
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>Total Amount:</td>
                <td>16,000</td>
            
            </tr>
            </tbody>
        </v-table>
        <div class="flex float-right mt-4">
            <button class="block float-right rounded-lg bg-gray-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-gray-700 focus:outline-none focus:ring"
                type="button"
                >
                <v-icon name="fa-print" />
                </button>
        </div>
        <br><br>
    </div>
</template>

<style scoped>
@media print{
  *{ display: none; }
  table { display: block; }
}
</style>