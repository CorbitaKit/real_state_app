<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { ref } from 'vue'
import FileUpload from '../components/fileupload.vue'
import { useForm } from '@inertiajs/vue3'
import {getUser} from '../plugins/get-user-plugin'
import moment from 'moment'
import { useToaster } from '../composables/toast'


const { getUserInfo } = getUser()
const { show } = useToaster()

const user = getUserInfo()
const props = defineProps({
    lots: Object
})

const mode_of_payments = ref([
    {
        mode_of_payment: 'GCash',
    },
    {
        mode_of_payment: 'Bank Transfer',
    }
])

const form = useForm({
    file: {},
    lot_id: {},
    mode_of_payment: '',
    amount: 0,
    user_id: user.id,
    status: 'Pending'
})

defineOptions({layout: Layout})

const submit = () => {
    form.transform((data) => {
      
        return {
            ...data,
           mode_of_payment: data.mode_of_payment.mode_of_payment,
           date_of_payment: moment().format('YYYY-MM-DD')
        };
    }).post('/payments', {
        onSuccess: (() => {
            show('success', 'Success', 'Payment submitted successfully!')
            router.get('/payments')
        })
    });
}

</script>


<template>
    <Header  :displayBtn="false" :title="'Make Payment'" />
    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
        <Toast />
        <form-wizard step-size="xs" @on-complete="submit">
            <tab-content title="List of lots">
                <div class="grid grid-cols-3 gap-3">
                    <div class="mb-4" v-for="lot in lots" :key="lot.id">
                        <button @click="form.lot_id = lot.id" type="button" class="block w-full items-center rounded p-4 text-sm font-medium transition hover:scale-105" :class="lot.color_label">
                            <span> Lot {{ lot.id }}</span><br>
                        </button>
                    </div>
                </div>
            </tab-content>
            <tab-content title="Payment Details">
                <div class="grid grid-cols-2 gap-4 mx-9">
                    <div class="mb-4">
                        <label for="phase" class="block text-gray-700 font-semibold mb-2">Amount</label>
                        <InputNumber v-model="form.amount" inputId="currency-ph" mode="currency" currency="PHP" locale="en-PH" fluid  class="w-full"/>   
                    </div>
                    <div class="mb-4">
                        <label for="region" class="block text-gray-700 font-semibold mb-2">Mode of payment</label>
                        <Dropdown v-model="form.mode_of_payment" :options="mode_of_payments" filter optionLabel="mode_of_payment" placeholder="Select a mode of payment" class="w-full md:w-full border" />
                    </div>
                </div>
            </tab-content>
            <tab-content title="Upload Payment">
                <FileUpload :file="form.file" />
            </tab-content>
           
        </form-wizard>
    </div>
    
</template>