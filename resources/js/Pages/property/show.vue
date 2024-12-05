<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'

import Card from './components/card.vue'
import {getUser} from '../plugins/get-user-plugin'
import { useConfirm } from "primevue/useconfirm";
import { useForm, router } from '@inertiajs/vue3';
import moment from 'moment';
import { useToaster } from '../composables/toast'
import { ref } from 'vue';
import Swal from 'sweetalert2'

const { show } = useToaster()
const { getUserInfo } = getUser()
const confirm = useConfirm();


const user = getUserInfo()
const visible = ref(false)
const payments = ref()
const payment_view = ref(false)
const admin_apply = ref(false)
const props = defineProps({
    property: Object,
    clients: Object
})
const form = useForm({
    application_type: 'Lot Application',
    lot_id: 0,
    user_id: user.id,
    reserved_date: moment().format('YYYY-MM-DD'),
    status: 'For Review'
})


const checkUser = (lot) => {
    if (user.role.name === 'Client') {
        applyForLot(lot)
    } else {
        form.lot_id = lot.id
        admin_apply.value = true

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
    console.log(form)
    form.post('/applications', {
        onSuccess: (() => {
            if (admin_apply) {
                admin_apply.value = false
                Swal.fire({
                    title: "Success!",
                    text: "Application sent succesfully!",
                    icon: "success"
                });
            } else {
                Swal.fire({
                title: "Success!",
                text: "We will review your application, and will send an email after reviewing",
                icon: "success"
            });
            }
            
        })
    })
}
const calculatePercentage = (lot) => {
    const total_amount = lot.lot_group.sqr_meter * lot.lot_group.amount_per_sqr_meter;
    const total_payment = lot.payments.reduce((sum, payment) => sum + payment.amount, 0);
    
    const percentage = (total_payment / total_amount) * 100;
    return percentage.toFixed(2);
}

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
      }).format(value);
}
const home = (route) => {
    router.get(route)
}

const showPayments = (lotPayments) => {
    payments.value = lotPayments
    payment_view.value = true
}

defineOptions({layout: Layout})
</script>

<template>
    <div class="row">
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    <h1>Applications</h1>
                </div>
                
                <div class="ml-auto d-flex align-items-center">
                    
                    <nav>
                        <ol class="breadcrumb p-0 m-b-0">
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/dashboard')"><i class="ti ti-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/applications')">Applicaitons</a>
                            </li>
                        
                            <li class="breadcrumb-item active text-primary" aria-current="page">Property</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
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
                <td>{{ formatCurrency(payment.amount) }}</td>
                <td>{{ payment.mode_of_payment }}</td>
                <td>{{ payment.date_of_payment }}</td>
                <td>{{ payment.invoice_number }}</td>
            </tr>
            </tbody>
        </v-table>
    </Dialog>
    <Dialog v-model:visible="visible" modal header="Site visit" :style="{ width: '50rem' }">
        <span class="tw-text-surface-500 tw-dark:text-surface-400 tw-block mb-8">Set a date for your site visit.</span>
        <div class="tw-flex items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Date</label>
            <Calendar v-model="form.reserved_date" class="tw-w-full tw-rounded-md"/>
        </div>
        <div class="tw-flex tw-justify-end tw-gap-2">
            <Button type="button" label="Save" @click="siteVisit"></Button>
        </div>
    </Dialog>

    <Dialog v-model:visible="admin_apply" modal header="Select Client" :style="{ width: '50rem' }">
        <div class="tw-flex items-center tw-gap-4 tw-mb-4">
            <select  class="js-basic-single form-control" name="region" v-model="form.user_id">
                <option  v-for="client in clients" :key="client.id" :value="client.id">{{ client.personal_info.first_name }} {{ client.personal_info.last_name }}</option>
            </select>
            
        </div>
        <div class="tw-flex tw-justify-end tw-gap-2">
            <Button type="button" label="Save" @click="sendApplication"></Button>
        </div>
    </Dialog>


    <div class="row">
        <div class="col-xl-12">
            <div class="card card-statistics border-0 shadow-none mb-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-border-3">
                            <thead>
                                <tr>
                                    <th>Lot Number</th>
                                    <th>Block</th>
                                    <th>Sqr Meters</th>
                                    <th>Amount Per Sqr Meters</th>
                                    <th>Monthly Amortization</th>
                                    <th>Payment Percentage</th>
                                    <th>Status</th>
                                    <th v-if="user.role.name !== 'Client'">Client</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="mb-0">
                                <tr v-for="lot in property.lots" :key="lot.id">
                                    <td>
                                        {{ lot.name }}
                                        
                                    </td>
                                    <td>
                                        Block {{ lot.block }}
                                    </td>
                                    <td>
                                        {{ lot.lot_group.sqr_meter }} m&sup2;
                                    </td>
                                    <td>
                                        {{ formatCurrency(lot.lot_group.amount_per_sqr_meter) }}
                                    </td>
                                    <td>
                                        {{ formatCurrency(lot.lot_group.monthly_amortizations) }}
                                    </td>
                                    <td>
                                        <ProgressBar :value="calculatePercentage(lot)" />
                                    </td>
                                    <td >
                                        <span class="badge badge-info" v-if="lot.status === 'Available'">
                                            {{ lot.status }}
                                        </span>
                                        <span class="badge badge-warning" v-else-if="lot.status === 'Pending'">
                                            {{ lot.status }}
                                        </span>
                                        <span class="badge badge-success" v-else-if="lot.status === 'Occupied'">
                                            {{ lot.status }}
                                        </span>
                                    </td>
                                        
                                    <td v-if="user.role.name !== 'Client'">
                                        <span v-if="lot.user">
                                            {{ lot.user.personal_info.first_name }}
                                            {{ lot.user.personal_info.last_name }} 
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="p-2" href="#!" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
                                                <h6 class="mb-1">Action</h6>
                                                <a v-if="user.role.name !=='Client'" @click="showPayments(lot.payments)" class="dropdown-item" href="#"><i class="fa-fw far fa-file-alt pr-2"></i>View Payment History</a>
                                                <a v-if=" lot.status === 'Available'" @click.prevent="checkUser(lot)" class="dropdown-item" href="#!"><i class="fa-fw far fa-file-pdf pr-2"></i>Apply</a>
                                                <a v-if="user.role.name === 'Client'" @click.prevent="visible = true" class="dropdown-item" href="#!"><i class="fa-fw far fa-calendar pr-2"></i>Request a site visit</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
