<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import {getUser} from '../plugins/get-user-plugin'

import { useVueToPrint } from "vue-to-print";
const { getUserInfo } = getUser()
const user = getUserInfo()

const props = defineProps({
    payments: Object,
    clients: Object
})
const transactionRef = ref()
const isPrinting = ref(false)
const form = useForm({
    date_from: '',
    date_to: '',
    client: '',
    mode_of_payment: ''
})

const { handlePrint } = useVueToPrint({
  content: transactionRef,
  documentTitle: "AwesomeFileName",
});

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

const filterReport = () => {
    form.post('/reports/filter')
}
const generateTodaysDate = () => {
    const today = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = today.toLocaleDateString('en-US', options);

    return formattedDate
}
</script>

<template>
    <div class="row">
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    Reports
                </div>

                <div class="ml-auto d-flex align-items-center">

                    <nav>
                        <ol class="breadcrumb p-0 m-b-0">
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/dashboard')"><i class="ti ti-home"></i></a>
                            </li>

                            <li class="breadcrumb-item active text-primary" aria-current="page">Reports</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>

    <div class="tw-mx-auto tw-bg-white tw-p-8 tw-my-8 tw-rounded tw-shadow-md tw-flex tw-content-between tw-items-center">
        <div class="tw-mb-4 tw-m-2">
            <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Date From</label>
            <Calendar v-model="form.date_from" class="tw-w-full tw-rounded-md" style="border-radius: 7px;"/>
        </div>

        <div class="tw-mb-4 tw-m-2">
            <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Date To</label>
            <Calendar v-model="form.date_to" class="tw-w-full tw-rounded-md" style="border-radius: 7px;"/>
        </div>

        <div class="tw-mb-4 tw-m-2">
            <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Clients</label>
            <select  class="js-basic-single form-control tw-w-full" name="region" v-model="form.client">
                <option  v-for="client in clients" :value="client.personal_info">{{ client.personal_info.first_name }} {{ client.personal_info.last_name }}</option>
            </select>
        </div>
        <div class="tw-mb-4 tw-mt-3">
            <label for="region" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Mode of payment</label>
            <select  class="js-basic-single form-control tw-w-full" name="region" v-model="form.mode_of_payment">
                <option  v-for="mode_of_payment in mode_of_payments" :value="mode_of_payment.mode_of_payment">{{ mode_of_payment.mode_of_payment }}</option>
            </select>
        </div>
        <div class="tw-mb-4 tw-mt-3 tw-ml-3">
            <label for="region" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2 tw-text-white">Filter</label>
            <button @click="filterReport" type="button" class="btn btn-secondary">Filter</button>
        </div>
    </div>
    <div class="tw-mx-auto tw-bg-white tw-p-8 tw-my-8 tw-rounded tw-shadow-md">
        <div ref="transactionRef">
            <v-table class="responsive">
                <tbody>
                    <tr>
                        <td></td>
                        <td>
                            <img src="/header.png" style="height: 100px;"/>
                        </td>
                        <td>
                            <h1 style="margin-left:100px;">JEFF ALDEBAL REALTY SERVICE</h1>
                        Door 3, CEASAR APARMENT, Sto. Niño, Carmen, Davao del Norte
                        </td>
                    </tr>
                </tbody>
            </v-table>
            <h1 class="text-center">Sales Report</h1>
            <v-table>
                <tbody>

                    <tr>

                        <td>
                            PREPARED BY: {{ user.personal_info.first_name }} {{  user.personal_info.last_name }}
                        </td>
                        <td>
                            GENERATED ON: {{ generateTodaysDate() }}
                        </td>
                    </tr>
                </tbody>
            </v-table>
            <v-table>
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
                    Invoice Number
                    </th>
                    <th class="text-left">
                    Amount
                    </th>

                </tr>
                </thead>
                <tbody>
                <tr v-for="payment in payments">
                    <td>{{payment.user?.personal_info?.first_name}} {{payment.user?.personal_info?.last_name}}</td>

                    <td>{{ payment.mode_of_payment }}</td>
                    <td>{{ payment.date_of_payment }}</td>
                    <td>{{ payment.invoice_number }}</td>
                    <td>{{ payment.amount }}</td>
                </tr>

                </tbody>
            </v-table>
        </div>
        <span>THIS IS SYSTEM GENERATED REPORT</span>

    </div>
    <button @click="handlePrint" type="button" class="btn btn-secondary pull-right">Print</button>
</template>

