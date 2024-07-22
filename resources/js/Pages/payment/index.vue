<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import {getUser} from '../plugins/get-user-plugin'
import { router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    payments: Object
})

const { getUserInfo } = getUser()

const user = getUserInfo()
const form = useForm({
    status: ''
})

defineOptions({layout: Layout})

const submit = () => {
    router.get('/payments/create')
}

const confirmPayment = (payment_id) => {
    form.status = 'Confirmed'
    form.patch('/payments/'+payment_id, {
        onSuccess: (() => {
            alert("WOW")
        })
    })
}
</script>

<template>
    <Header @submit="submit"  :displayBtn="true" :btnTxt="'Make Payment'" :title="'Payment List'" />
     <div class="relative overflow-x-auto mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Payment Proof
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Payment Type
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Payment Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Payment For
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Amount
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th v-if="user.role.name == 'Admin'" scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="payment in payments" :key="payment.id">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        <!-- {{ payment.file }} -->
                        <Image alt="Image" preview>
                            <template #previewicon>
                                <i class="pi pi-search"></i>
                            </template>
                            <template #image>
                                <img :src="'/storage/'+payment.file.url" alt="image" class="h-[50px]"/>
                            </template>
                            <template #preview="slotProps">
                                <img :src="'/storage/'+payment.file.url" alt="preview" :style="slotProps.style" @click="slotProps.onClick" />
                            </template>
                        </Image>
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ payment.mode_of_payment }}
                    </th>
                    <td class="px-6 py-4">
                        {{ payment.date_of_payment }}
                    </td>
                    <td class="px-6 py-4">
                        Phase {{ payment.lots[0].property.phase }},
                        purok {{ payment.lots[0].property.purok }},
                        barangay {{ payment.lots[0].property.barangay }},<br>
                        {{ payment.lots[0].property.city }},
                        {{ payment.lots[0].property.province }},
                        Lot {{ payment.lots[0].id }} 
                     
                       
                    </td>
                    <td class="px-6 py-4">
                        {{ payment.amount }}
                    </td>
                    <td class="px-6 py-4">
                        {{ payment.status }}
                    </td>
                    <td class="px-6 py-4" v-if="user.role.name == 'Admin'">
                        <v-icon v-if="payment.status === 'Pending'" @click="confirmPayment(payment.id)"  name="fc-approval" animation="ring" scale="2" class="text-4xl rounded cursor-pointer block float-left mr-2"/>                     
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>