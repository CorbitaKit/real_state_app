<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import {getUser} from '../plugins/get-user-plugin'
import { router, useForm } from '@inertiajs/vue3'
import FileUpload from '../components/fileupload.vue'
import { useToaster } from '../composables/toast'


const { show } = useToaster()
import { ref } from 'vue'

const props = defineProps({
    payments: Object
})

const { getUserInfo } = getUser()

const user = getUserInfo()
const visible = ref(false)
const payment_id = ref()
const form = useForm({
    status: '',
    invoice_number: '',
    file: {}
})

defineOptions({layout: Layout})

const submit = () => {
    router.get('/payments/create')
}

const confirmPayment = (id) => {
    visible.value = true
    payment_id.value = id
    
}
const submitPayment = () => {
    form.status = 'Confirmed'
    form.post('/payments/update/'+payment_id.value, {
        onSuccess: (() => {
            show('success', 'Success', 'Payment Confirmed!')
            visible.value = false
            form.reset()
        })
    })
}
const setButton = () => {
    if (user.role_id === 1) {
        return false
    }
    return true
}
</script>

<template>
    <Header @submit="submit"  :displayBtn="setButton()" :btnTxt="'Make Payment'" :title="'Payment List'" />
    <Toast />
    <Dialog v-model:visible="visible" modal header="Confirm Payment" :style="{ width: '50rem' }">
        <span class="text-surface-500 dark:text-surface-400 block mb-8">Please input invoice # number and attach the actual invoice</span>
        <div class="flex items-center gap-4 mb-4">
            <label for="username" class="font-semibold w-24">Invoice Number</label>
            <InputText v-model="form.invoice_number" class="w-full rounded-md"/>
           
        </div>
        <FileUpload :file="form.file" />
        <div class="flex justify-end gap-2">
            <Button type="button" label="Save" @click="submitPayment"></Button>
        </div>
    </Dialog>
     <div class="relative overflow-x-auto mt-5">
        <v-table>
            <thead>
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
                    <th scope="col" class="px-6 py-3">
                        Invoice Number
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Invoice
                    </th>
                    <th v-if="user.role_id != 3" scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
               
                <tr v-for="payment in payments" :key="payment.id">
                    <th scope="row">
           
                        <Image alt="Image" preview>
                            <template #previewicon>
                                <i class="pi pi-search"></i>
                            </template>
                            <template #image>
                                <img :src="'/storage/'+payment.files[0]?.url" alt="image" class="h-[50px]"/>
                            </template>
                            <template #preview="slotProps">
                                <img :src="'/storage/'+payment.files[0]?.url" alt="preview" :style="slotProps.style" @click="slotProps.onClick" />
                            </template>
                        </Image>
                    </th>
                    <th scope="row">
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
                    <td class="px-6 py-4">
                        {{ payment.invoice_number }}
                    </td>
                    <td class="px-6 py-4">
                        <Image alt="Image" preview v-if="payment.status == 'Confirmed'">
                            <template #previewicon>
                                <i class="pi pi-search"></i>
                            </template>
                            <template #image>
                                <img :src="'/storage/'+payment.files[1]?.url" alt="image" class="h-[50px]"/>
                            </template>
                            <template #preview="slotProps">
                                <img :src="'/storage/'+payment.files[1]?.url" alt="preview" :style="slotProps.style" @click="slotProps.onClick" />
                            </template>
                        </Image>
                    </td>

                    <td class="px-6 py-4" v-if="user.role_id != 3">
                        <v-icon v-if="payment.status === 'Pending'" @click="confirmPayment(payment.id)"  name="fc-approval" animation="ring" scale="2" class="text-4xl rounded cursor-pointer block float-left mr-2"/>                     
                    </td>
                </tr>
            </tbody>
        </v-table>
    </div>
</template>