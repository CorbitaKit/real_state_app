<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'

import Card from './components/card.vue'
import {getUser} from '../plugins/get-user-plugin'
import { useConfirm } from "primevue/useconfirm";
import { useForm } from '@inertiajs/vue3';
import moment from 'moment';
import { useToaster } from '../composables/toast'
import { ref } from 'vue';

const { show } = useToaster()
const { getUserInfo } = getUser()
const confirm = useConfirm();


const user = getUserInfo()
const visible = ref(false)
const payments = ref()
const payment_view = ref(false)
const props = defineProps({
    property: Object
})
const form = useForm({
    application_type: 'Lot Application',
    lot_id: 0,
    user_id: user.id,
    reserved_date: moment().format('YYYY-MM-DD'),
    status: 'For Review'
})


const checkUser = (lot) => {
    if (user.role_id !== 3 && lot.status !== 'Available') {
        if (lot.payments) {
            payment_view.value = true
            payments.value = lot.payments
        }
    }
    if (user.role_id === 3) {
        if (!user.personal_info) {
            confirm.require({
                message: 'You did not set up your profile yet. Please set up your profile first to continue',
                header: 'Confirmation',
                icon: 'pi pi-exclamation-triangle',
                
            });
        } else {
            applyForLot(lot)
        }
    }
}

const applyForLot = (lot) => {
    if (lot.status === 'Available') {
        confirm.require({
            message: 'Are you sure you want to apply for this lot?',
            header: 'Confirmation',
            icon: 'pi pi-exclamation-triangle',
            rejectProps: {
                label: 'Cancel',
                severity: 'secondary',
                outlined: true
            },
            acceptProps: {
                label: 'Save'
            },
            accept: () => {
                form.lot_id = lot.id
                sendApplication()
            },
        });
    }
}
const siteVisit = () => {
    form.application_type = 'Site Visit',
    form.reserved_date = moment(form.reserved_date).format('YYYY-MM-DD')
    form.lot_id = props.property.lots[0].id
    sendApplication()
    visible.value = false
}
const sendApplication = () => {
    form.post('/applications', {
        onSuccess: (() => {
            show('success', 'Application Sent!', 'We will review your application, and will send an email after reviewing')
        })
    })
}
defineOptions({layout: Layout})
</script>

<template>
    <Header  :displayBtn="false" :title="'Property Details'" />
    <ConfirmDialog />
    <Toast />
    <Dialog v-model:visible="payment_view" modal header="Payment History" :style="{ width: '50rem' }">
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
          Invoice Number
        </th>

      </tr>
    </thead>
    <tbody>
      <tr v-for="payment in payments" :key="payment.id" >
        <td>{{ payment.amount }}</td>
        <td>{{ payment.mode_of_payment }}</td>
        <td>{{ payment.date_of_payment }}</td>
        <td>{{ payment.invoice_number }}</td>
      </tr>
    </tbody>
  </v-table>
    </Dialog>
    <Dialog v-model:visible="visible" modal header="Site visit" :style="{ width: '50rem' }">
        <span class="text-surface-500 dark:text-surface-400 block mb-8">Set a date for your site visit.</span>
        <div class="flex items-center gap-4 mb-4">
            <label for="username" class="font-semibold w-24">Date</label>
            <Calendar v-model="form.reserved_date" class="w-full rounded-md"/>
        </div>
        <div class="flex justify-end gap-2">
            <Button type="button" label="Save" @click="siteVisit"></Button>
        </div>
    </Dialog>
    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
        
        <Card :property="property" :displayBtn="false" class="mb-5"/>
        <p class="text-black font-medium text-1xl pt-2 mb-2" v-if="user.role_id == 3">
            Note: This is not the actual map of the property, please refer to this 
            <a class="text-blue" style="color:blue;" href="/test.jpg" target="__blank">image</a> or request a
            <a href="#" @click="visible = true" style="color:blue;">site visit</a>
        </p>

        <div class="grid grid-cols-4 gap-3 mb-4 ">
            <div class="mb-4 border-2 p-4"  v-for="lot_group in property.lot_groups" :key="lot_group.id">
                
                <button  type="button" class="block w-full items-center rounded p-4 text-sm font-medium transition hover:scale-105" :class="lot_group.color_label">
                    <span>{{ lot_group.amount_per_sqr_meter }} - Per sqr meter</span><br>
                    <span>{{ lot_group.sqr_meter }} sqr meter</span>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-4 gap-3">
            
            <div class="mb-4" v-for="lot in property.lots" :key="lot.id">
                
                <button @click="checkUser(lot)" type="button" class="block w-full items-center rounded p-4 text-sm font-medium transition hover:scale-105" :class="lot.lot_group.color_label">
                    <span> Lot {{ lot.id }}</span><br>
                    <span> Monthly Payment: {{ lot.lot_group.monthly_amortizations }} </span><br>
                    <span> Total Amount:  {{ lot.lot_group.sqr_meter * lot.lot_group.amount_per_sqr_meter }}</span><br>
                    <span> Status: {{ lot.status }}</span><br>
                    <span v-if="lot.user && lot.status === 'Pending'"> Applied By: {{ lot.user.personal_info.first_name }} {{ lot.user.personal_info.last_name }}</span>
                    <span v-if="lot.user && lot.status === 'Occupied'"> Owned By: {{ lot.user.personal_info.first_name }} {{ lot.user.personal_info.last_name }}</span>
                    
                </button>
            </div>
            
        </div>
    </div>
    
</template>
