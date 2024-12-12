<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import {getUser} from '../plugins/get-user-plugin'
import { router, useForm } from '@inertiajs/vue3'
import FileUpload from '../components/fileupload.vue'
import { useToaster } from '../composables/toast'
import Swal from 'sweetalert2'
import { formatCurrency } from '../composables/currencyFormatter'
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
            Swal.fire({
                title: "Success!",
                text: "Payment confirmed!",
                icon: "success"
            });
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
    <!-- <Header @submit="submit"  :displayBtn="setButton()" :btnTxt="'Make Payment'" :title="'Payment List'" /> -->
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

                            <li class="breadcrumb-item active text-primary" aria-current="page">Payments</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
    <Toast />
    <Dialog v-model:visible="visible" modal header="Confirm Payment" :style="{ width: '50rem' }">
        <span class="tw-text-surface-500 tw-dark:text-surface-400 tw-block tw-mb-8">Please input invoice # number and attach the actual invoice</span>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Invoice Number</label>
            <InputText v-model="form.invoice_number" class="tw-w-full tw-rounded-md"/>

        </div>
        <FileUpload :file="form.file" />
        <div class="tw-flex tw-justify-end tw-gap-2">
            <Button type="button" label="Save" @click="submitPayment"></Button>
        </div>
    </Dialog>
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-statistics clients-contant">
                <div class="card-header">
                    <div class="d-xxs-flex justify-content-between align-items-center">
                        <div class="card-heading">
                            <h5 class="card-title">Payment Lists</h5>
                        </div>

                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table mb-0 table-border-3">
                        <thead>
                            <tr>
                                <th scope="col">
                                    Invoice Number
                                </th>
                                <th scope="col">
                                    Amount
                                </th>
                                <th scope="col">
                                    Invoice
                                </th>

                                <th scope="col">
                                    Payment Type
                                </th>
                                <th scope="col">
                                    Payment Date
                                </th>
                                <th scope="col">
                                    Payment For
                                </th>

                                <th scope="col">
                                    Status
                                </th>

                                <th scope="col">
                                    Payment Proof
                                </th>
                                <th v-if="user.role_id != 3" scope="col">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr v-for="payment in payments" :key="payment.id">
                                <td >
                                    {{ payment.invoice_number }}
                                </td>
                                <td >
                                    {{formatCurrency(payment.amount) }}
                                </td>
                                <td >
                                    <Image alt="Image" preview v-if="payment.status == 'Confirmed'">
                                        <template #previewicon>
                                            <i class="pi pi-search"></i>
                                        </template>
                                        <template #image>
                                            <img :src="'/storage/'+payment.files[1]?.url" alt="image" class="tw-h-[50px]"/>
                                        </template>
                                        <template #preview="slotProps">
                                            <img :src="'/storage/'+payment.files[1]?.url" alt="preview" :style="slotProps.style" @click="slotProps.onClick" />
                                        </template>
                                    </Image>
                                </td>

                                <td>
                                    {{ payment.mode_of_payment }}
                                </td>
                                <td >
                                    {{ payment.date_of_payment }}
                                </td>
                                <td>
                                    Phase {{ payment.lots[0].property.phase }},
                                    Purok {{ payment.lots[0].property.purok }},
                                    Barangay {{ payment.lots[0].property.barangay }},<br>
                                    {{ payment.lots[0].property.city }},
                                    {{ payment.lots[0].property.province }},
                                    Lot {{ payment.lots[0].id }}

                                </td>

                                <td >
                                    {{ payment.status }}
                                </td>

                                <td>

                                    <Image alt="Image" preview>
                                        <template #previewicon>
                                            <i class="pi pi-search"></i>
                                        </template>
                                        <template #image>
                                            <img :src="'/storage/'+payment.files[0]?.url" alt="image" class="tw-h-[50px]"/>
                                        </template>
                                        <template #preview="slotProps">
                                            <img :src="'/storage/'+payment.files[0]?.url" alt="preview" :style="slotProps.style" @click="slotProps.onClick" />
                                        </template>
                                    </Image>
                                </td>

                                <td  v-if="user.role_id != 3">
                                    <!-- <v-icon v-if="payment.status === 'Pending'" @click="confirmPayment(payment.id)"  name="fc-approval" animation="ring" scale="2" class="text-4xl rounded cursor-pointer block float-left mr-2"/>-->
                                    <div class="dropdown" v-if="payment.status == 'Pending'">
                                        <a class="p-2" href="#!" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-more-horizontal"></i>
                                        </a>
                                        <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
                                            <h6 class="mb-1">Action</h6>
                                            <a v-if="payment.status === 'Pending'" @click="confirmPayment(payment.id)" class="dropdown-item" href="#"><i class="fa-fw far fa-check-circle pr-2"></i>Acknowledge</a>
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
</template>
