<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    payments: Object
})
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

const filterReport = () => {
    form.post('/reports/filter')
}

const printDiv = () => {
    const printContent = document.getElementById("printMe").outerHTML;
    const printWindow = window.open("", "_blank");
    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Report</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                        text-align: left;
                    }
                </style>
            </head>
            <body>${printContent}</body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
};
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
            <InputText type="text" v-model="form.client" class="tw-block tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border " placeholder="... Search Client"/>        
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
    <div class="tw-mx-auto tw-bg-white tw-p-8 tw-my-8 tw-rounded tw-shadow-md"  >
      
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
                Invoice Number
                </th>
                <th class="text-left">
                Amount
                </th>

            </tr>
            </thead>
            <tbody>
            <tr v-for="payment in payments">
                <td>{{payment.user.personal_info.first_name}} {{payment.user.personal_info.last_name}}</td>
                
                <td>{{ payment.mode_of_payment }}</td>
                <td>{{ payment.date_of_payment }}</td>
                <td>{{ payment.invoice_number }}</td>
                <td>{{ payment.amount }}</td>
            
            </tr>
            
            </tbody>
        </v-table>
    </div>
    <button @click="printDiv" type="button" class="btn btn-secondary pull-right">Print</button>
</template>

<style scoped>
@media print{
  *{ display: none; }
  table { display: block; }
}
</style>