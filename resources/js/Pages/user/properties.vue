<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { ref } from 'vue'
import { formatCurrency } from '../composables/currencyFormatter'
import { useVueToPrint } from "vue-to-print";
import Swal from 'sweetalert2'
import axios from 'axios'
defineOptions({layout: Layout})
import { router } from '@inertiajs/vue3'


const visible = ref(false)
const transferOwnership = ref(false)
const payments = ref()
const payment_plan = ref(false)
const payment_plans = ref()
const lot_id = ref()
const user_id = ref()
const props = defineProps({
    client_lots: Object,
    users: Object
})
const chartOfAccountRef = ref()


const { handlePrint } = useVueToPrint({
  content: chartOfAccountRef,
  documentTitle: "AwesomeFileName",
});

const calculateTotalAmount = (lot_group) => {
    return lot_group.sqr_meter * lot_group.amount_per_sqr_meter
}

const calculateRemainingBalance = (lot) => {
    const total_amount = calculateTotalAmount(lot.lot_group)
    const total_payment = lot.payments.reduce((sum, payment) => sum + payment.amount, 0)

    return total_amount - total_payment
}

const viewPaymentHistory = (lot_payments) => {
    visible.value = true
    payments.value = lot_payments
}

const viewPaymentPlan = (lot_payment_plans) => {
    payment_plan.value = true
    payment_plans.value = lot_payment_plans
}


const confirmTransfer = (user) => {
    transferOwnership.value = false
    Swal.fire({
        title: "Are you sure?",
        text: "You want to transfer ownership for this user!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, transfer it!"
    }).then((result) => {
    if (result.isConfirmed) {
        axios.get('/lot-transfer/' + user_id.value + '/' + lot_id.value)
        .then(res => {
                Swal.fire({
                title: "Deleted!",
                text: "Lot ownership transfered successully.",
                icon: "success"
            });

            router.get('/lots/get-client-lots')
        })



    }
    });
}

const doTransfer = (lot) => {
    lot_id.value = lot.id
    transferOwnership.value = true
}
</script>

<template>
     <Dialog v-model:visible="transferOwnership" modal header="Confirm Payment" :style="{ width: '50rem' }">
        <span class="tw-text-surface-500 tw-dark:text-surface-400 tw-block tw-mb-8">Please Select User</span>

        <select  class="js-basic-single form-control" name="region" v-model="user_id" @change="confirmTransfer">
            <option  v-for="(user, i) in users" :value="user.id" :key="i">{{ user.personal_info?.first_name }} &nbsp; {{ user.personal_info?.last_name }}</option>
        </select>
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
    <Dialog v-model:visible="payment_plan" modal header="Payment Plan" :style="{ width: '60rem' }">
        <div ref="chartOfAccountRef">
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
                            <h1 class="text-center">CHART OF ACCOUNTS</h1>
            <v-table>
                <thead>
                <tr>
                    <th class="text-left">
                    Due Date
                    </th>
                    <th class="text-left">
                        Amount
                    </th>


                    <th class="text-left">
                        Payment Date
                    </th>

                    <th class="text-left">
                        Amount Paid
                    </th>

                    <th class="text-left">
                        Mode of Payment
                    </th>

                    <th class="text-left">
                        Status
                    </th>
                    <th class="text-left">
                        Invoice Number
                    </th>

                </tr>
                </thead>
                <tbody>
                    <tr v-for="plan in payment_plans">
                        <td>
                            {{ plan.due_date }}
                        </td>
                        <td>
                            {{ formatCurrency(plan.lot.lot_group.monthly_amortizations) }}
                        </td>
                        <td>
                            <span v-if="plan.payment">{{ plan.payment.date_of_payment }}</span>
                            <span v-else>-</span>
                        </td>
                        <td>
                            <span v-if="plan.payment">{{ formatCurrency(plan.payment.amount) }}</span>
                            <span v-else>-</span>
                        </td>
                        <td>
                            <span v-if="plan.payment">{{ plan.payment.mode_of_payment }}</span>
                            <span v-else>-</span>
                        </td>
                        <td>
                            <span v-if="plan.payment">{{ plan.payment.status }}</span>
                            <span v-else>-</span>
                        </td>
                        <td>
                            <span v-if="plan.payment">{{ plan.payment.invoice_number }}</span>
                            <span v-else>-</span>
                        </td>

                    </tr>
                </tbody>
            </v-table>
        </div>
        <button class="btn btn-block btn-info" @click="handlePrint">Print</button>
    </Dialog>
    <!-- <div class="tw-mx-auto tw-bg-whitetw- tw-p-8 tw-my-8 tw-rounded tw-shadow-md">
        <div class="tw-relative tw-overflow-x-auto tw-mt-5">
        <table class="tw-w-full tw-text-sm tw-text-left tw-rtl:text-right tw-text-gray-500 tw-dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col">
                        Property
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Square Meter
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Amount Per Square Meter
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total Amount
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Remaining Balance
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="lot in client_lots" :key="lot.id">
                    <td class="px-6 py-4">

                        Phase {{ lot.property.phase }},
                        purok {{ lot.property.purok }},
                        barangay {{ lot.property.barangay }},
                        {{ lot.property.city }},
                        {{ lot.property.province }},
                        {{ lot.name }}




                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                      {{ lot.lot_group.sqr_meter }} Square Meters
                    </th>
                    <td class="px-6 py-4">
                        {{ lot.lot_group.amount_per_sqr_meter }}
                    </td>

                    <td class="px-6 py-4">
                        {{ calculateTotalAmount(lot.lot_group) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ calculateRemainingBalance(lot) }}
                    </td>
                    <td class="px-6 py-4">
                        <Button @click="viewPaymentHistory(lot.payments)" label="View Payments" severity="success" />
                    </td>



                </tr>
            </tbody>
        </table>
    </div>
    </div> -->

    <div class="row">
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    <h1>My Properties</h1>
                </div>

                <div class="ml-auto d-flex align-items-center">

                    <nav>
                        <ol class="breadcrumb p-0 m-b-0">
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/dashboard')"><i class="ti ti-home"></i></a>
                            </li>

                            <li class="breadcrumb-item active text-primary" aria-current="page">My Properties</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-statistics border-0 shadow-none mb-0">
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table mb-0 table-border-3">
                            <thead>
                                <tr>
                                    <th scope="col">
                                        Property
                                    </th>
                                    <th scope="col">
                                        Square Meter
                                    </th>
                                    <th scope="col">
                                        Amount Per Square Meter
                                    </th>
                                    <th scope="col">
                                        Total Amount
                                    </th>
                                    <th scope="col">
                                        Remaining Balance
                                    </th>

                                    <th scope="col">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="mb-0">
                                <tr  v-for="lot in client_lots" :key="lot.id">
                                    <td class="px-6 py-4">

                                        Phase {{ lot.property.phase }},
                                        purok {{ lot.property.purok }},
                                        barangay {{ lot.property.barangay }},
                                        {{ lot.property.city }},
                                        {{ lot.property.province }},
                                        {{ lot.name }}

                                    </td>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ lot.lot_group.sqr_meter }} Square Meters
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ formatCurrency(lot.lot_group.amount_per_sqr_meter) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ formatCurrency(calculateTotalAmount(lot.lot_group)) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ formatCurrency(calculateRemainingBalance(lot)) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <!-- <Button @click="viewPaymentHistory(lot.payments)" label="View Payments" severity="success" /> -->
                                        <div class="dropdown">
                                            <a class="p-2" href="#!" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
                                                <h6 class="mb-1">Action</h6>
                                                <a @click="viewPaymentHistory(lot.payments)" class="dropdown-item" href="#"><i class="fa-fw far fa-file-alt pr-2"></i>View Payment History</a>
                                                <a @click="viewPaymentPlan(lot.payment_plans)" class="dropdown-item" href="#!"><i class="fa-fw far fa-file-pdf pr-2"></i>View Payment Breakdown</a>
                                                <a @click="doTransfer(lot)" class="dropdown-item" href="#!"><i class="fa-fw far fa-file-pdf pr-2"></i>Transfer Ownership</a>

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
