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

defineOptions({layout: Layout})
</script>

<template>
    <div class="row">
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    <h1>Properties</h1>
                </div>
                
                <div class="ml-auto d-flex align-items-center">
                    
                    <nav>
                        <ol class="breadcrumb p-0 m-b-0">
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/dashboard')"><i class="ti ti-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/properties')">Properties</a>
                            </li>
                        
                            <li class="breadcrumb-item active text-primary" aria-current="page">Property</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
    <!-- <ConfirmDialog />
    <Toast /> -->
    <!-- <Dialog v-model:visible="payment_view" modal header="Payment History" :style="{ width: '50rem' }">
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
    </Dialog> -->
    <!-- <Dialog v-model:visible="visible" modal header="Site visit" :style="{ width: '50rem' }">
        <span class="tw-text-surface-500 tw-dark:text-surface-400 tw-block mb-8">Set a date for your site visit.</span>
        <div class="tw-flex items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Date</label>
            <Calendar v-model="form.reserved_date" class="tw-w-full tw-rounded-md"/>
        </div>
        <div class="flex justify-end gap-2">
            <Button type="button" label="Save" @click="siteVisit"></Button>
        </div>
    </Dialog> -->
    <!-- <div class="tw-mx-auto tw-bg-white tw-p-8 tw-my-8 tw-rounded tw-shadow-md">
        
        <p class="tw-text-black tw-font-medium tw-text-1xl tw-pt-2 tw-mb-2" v-if="user.role_id == 3">
            Note: This is not the actual map of the property, please refer to this 
            <a class="text-blue" style="color:blue;" href="/test.jpg" target="__blank">image</a> or request a
            <a href="#" @click="visible = true" style="color:blue;">site visit</a>
        </p>

        <div class="tw-grid tw-grid-cols-4 tw-gap-3 mb-4 ">
            <div class="tw-mb-4 tw-border-2 tw-p-4 tw-text-lg tw-font-medium tw-text-black"  v-for="lot_group in property.lot_groups" :key="lot_group.id" :class="lot_group.color_label">
                <span>{{ lot_group.amount_per_sqr_meter }} - Per sqr meter</span><br>
                <span>{{ lot_group.sqr_meter }} sqr meter</span>

            </div>
        </div>
        <div class="table-responsive mb-4">
            <table class="table mb-0 table-border-3">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Property</th>
                        <th>Date </th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="mb-0">
                    <tr>
                        <td>
                            <div class="avatar avatar-lg">
                                <img class="img-fluid rounded mCS_img_loaded" src="assets/img/blog/01.jpg" alt="">
                            </div>
                        </td>
                        <td>#54981</td>
                        <td>Karla George</td>
                        <td>Eaton Place</td>
                        <td><i class="far fa-calendar-alt mr-2 text-success"></i> 20-01-2020</td>
                        <td>Rent</td>
                        <td><span class="badge badge-success ">Paid</span></td>
                        <td>
                            <a href="javascript:void(0)" class="mr-2"><i class="fas fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"></i></a>
                            <a href="javascript:void(0)"><i class="far fa-trash-alt" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="tw-grid tw-grid-cols-4 tw-gap-3">
            
            <div class="mb-4" v-for="lot in property.lots" :key="lot.id">
                
                <button @click="checkUser(lot)" type="button" class="tw-text-lg tw-font-medium tw-text-black tw-block tw-w-full tw-items-center tw-rounded tw-p-4 tw-text-sm tw-font-medium transition hover:scale-105" :class="lot.lot_group.color_label">
                    <span> Lot {{ lot.id }}</span><br>
                    <span> Monthly Payment: {{ lot.lot_group.monthly_amortizations }} </span><br>
                    <span> Total Amount:  {{ lot.lot_group.sqr_meter * lot.lot_group.amount_per_sqr_meter }}</span><br>
                    <span> Status: {{ lot.status }}</span><br>
                    <span v-if="lot.user && lot.status === 'Pending'"> Applied By: {{ lot.user.personal_info.first_name }} {{ lot.user.personal_info.last_name }}</span>
                    <span v-if="lot.user && lot.status === 'Occupied'"> Owned By: {{ lot.user.personal_info.first_name }} {{ lot.user.personal_info.last_name }}</span><br>
                    <span v-if="lot.user && lot.status === 'Occupied'">Remaining Balance: 2000</span><br>
                    <span v-if="lot.user && lot.status === 'Occupied'">Total percentage: 
                        <ProgressBar :value="calculatePercentage(lot)"></ProgressBar>
                    </span><br>
                </button>
            </div>
            
        </div>
    </div> -->
    

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-statistics border-0 shadow-none mb-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-border-3">
                            <thead>
                                <tr>
                                    <th>Lot Number</th>
                                    <th>Sqr Meters</th>
                                    <th>Amount Per Sqr Meters</th>
                                    <th>Monthly Amortization</th>
                                    <th>Status</th>
                                    <th v-if="user.role.name !== 'Client'">Client</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="mb-0">
                                <tr v-for="lot in property.lots">
                                    <td>
                                        {{ lot.name }}
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
                                    <td >
                                        <span class="badge badge-info" v-if="lot.status === 'Available'">
                                            {{ lot.status }}
                                        </span>
                                        <span class="badge badge-warning" v-else-if="lot.status === 'Reserve'">
                                            {{ lot.status }}
                                        </span>
                                        <span class="badge badge-success" v-else-if="lot.status === 'Occupied'">
                                            {{ lot.status }}
                                        </span>
                                    </td>
                                        
                                    <td v-if="user.role.name !== 'Client'">Rent</td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="p-2" href="#!" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
                                                <h6 class="mb-1">Action</h6>
                                                <a class="dropdown-item" href="#!"><i class="fa-fw far fa-file-alt pr-2"></i>View Payment History</a>
                                                <a v-if="user.role.name === 'Client'" class="dropdown-item" href="#!"><i class="fa-fw far fa-file-pdf pr-2"></i>Apply</a>
                                                <a v-if="user.role.name === 'Client'" class="dropdown-item" href="#!"><i class="fa-fw far fa-calendar pr-2"></i>Request a site visit</a>
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
