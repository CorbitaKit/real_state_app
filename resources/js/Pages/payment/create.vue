<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { ref } from 'vue'
import FileUpload from '../components/fileupload.vue'
import { useForm, router } from '@inertiajs/vue3'
import {getUser} from '../plugins/get-user-plugin'
import moment from 'moment'
import Swal from 'sweetalert2'
import { formatCurrency } from '../composables/currencyFormatter'

const { getUserInfo } = getUser()

const user = getUserInfo()
const props = defineProps({
    lots: Object
})

const remaining_balance = ref()
const mode_of_payments = ref([
    {
        mode_of_payment: 'GCash',
    },
    {
        mode_of_payment: 'Bank Transfer',
    }
])
const advance = ref(false)
const total = ref()
const form = useForm({
    file: {},
    lot_id: 0,
    mode_of_payment: '',
    amount: 0,
    user_id: user.id,
    status: 'Pending',
    reference_number: ''
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

            Swal.fire({
                title: "Success!",
                text: "Payment submitted successfully!",
                icon: "success"
            });
            router.get('/payments/get-by-user')
        })
    });
}

const calculateTotalAmount = (lot_group) => {
    return lot_group.sqr_meter * lot_group.amount_per_sqr_meter
}

const calculateRemainingBalance = (lot) => {
    const total_amount = calculateTotalAmount(lot.lot_group)
    const total_payment = lot.payments.reduce((sum, payment) => sum + payment.amount, 0)


    return total_amount - total_payment
}

const calculateTotalPayment = (lot) => {
    return lot.payments.reduce((sum, payment) => sum + payment.amount, 0)
}

const checkLotData = () => {
    if (form.lot_id == 0) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please select a lot first",
        });

        return false
    }
    return true
}

const checkAmountPaid = () => {
    console.log(form.amount)
    if (form.amount > remaining_balance.value || form.amount < total.value) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Entered amount should not be greater than remaining balance",
        });
        return false
    }
    return true


}

const setLot = (lot) => {
    form.lot_id = lot.id
    form.amount = lot.lot_group.monthly_amortizations
    total.value = lot.lot_group.monthly_amortizations
    remaining_balance.value = calculateRemainingBalance(lot)
}

const advancePayment = () => {

    advance.value = true
}
</script>


<template>
    <!-- <Header  :displayBtn="false" :title="'Make Payment'" /> -->
    <div class="row">
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    <h1>Payments</h1>
                </div>

                <div class="ml-auto d-flex align-items-center">

                    <nav>
                        <ol class="breadcrumb p-0 m-b-0">
                            <li class="breadcrumb-item">
                                <a href="#" @click="home"><i class="ti ti-home"></i></a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="#" @click="home">Payments</a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Make Payments</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
    <div class="tw-mx-auto tw-bg-white tw-p-8 tw-my-8 tw-rounded tw-shadow-md">
        <Toast />
        <form-wizard step-size="xs" @on-complete="submit">
            <tab-content title="List of lots" :before-change="checkLotData">
                <div class="tw-grid tw-grid-cols-3 tw-gap-3">
                    <div class="tw-mb-4" v-for="lot in lots" :key="lot.id">
                        <button :disabled="calculateRemainingBalance(lot) == 0" @click="setLot(lot)" type="button" class="tw-text-black tw-text-lg tw-font-medium tw-block tw-w-full tw-items-center tw-rounded tw-p-4 tw-text-sm tw-font-medium transition hover:scale-105" :class="lot.color_label">
                            <span> Lot {{ lot.id }}</span><br>
                            <span>Remaining Balance: {{ formatCurrency(calculateRemainingBalance(lot)) }}</span><br>
                            <span>Total Payment:  {{ formatCurrency(calculateTotalPayment(lot)) }} </span>
                        </button>
                    </div>
                </div>
            </tab-content>
            <tab-content title="Payment Details" :before-change="checkAmountPaid">
                <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mx-9">
                    <div class="tw-mb-4">
                        <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Amount</label>
                        <InputNumber :disabled="!advance" v-model="form.amount" inputId="currency-ph" mode="currency" currency="PHP" locale="en-PH" fluid  class="tw-w-full"/>
                        <a href="#" @click.prevent="advancePayment">Pay in Advance</a>
                    </div>
                    <div class="mb-4">
                        <label for="region" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Mode of payment</label>
                        <!-- <Dropdown v-model="form.mode_of_payment" :options="mode_of_payments" filter optionLabel="mode_of_payment" placeholder="Select a mode of payment" class="tw-w-full md:w-full tw-border" /> -->
                        <select  class="js-basic-single form-control" name="region" v-model="form.mode_of_payment">
                            <option  v-for="(payment, i) in mode_of_payments" :value="payment" :key="i">{{ payment.mode_of_payment }}</option>
                        </select>
                    </div>
                </div>
            </tab-content>
            <tab-content title="Upload Payment">
                <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mx-9">
                    <div class="tw-mb-4">
                        <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Reference Number</label>
                        <InputText  v-model="form.reference_number"  fluid  class="tw-w-full"/>

                    </div>
                    <div class="tw-mb-4">
                        <FileUpload :file="form.file" />
                    </div>
                </div>

            </tab-content>

        </form-wizard>
    </div>

</template>
