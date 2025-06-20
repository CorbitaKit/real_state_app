<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import FileUpload from '../components/fileupload.vue'
import { router, useForm } from '@inertiajs/vue3'
import { reactive, ref, watch } from 'vue'
import moment from 'moment'
import { transformAddress } from '../composables/sentenceCase.js'
import Swal from 'sweetalert2'
import Avatar from "vue3-avatar";
import {getUser} from '../plugins/get-user-plugin'
import { debounce } from "lodash";
import axios from 'axios'

import { useVueToPrint } from "vue-to-print";

const props = defineProps({
    clients: Object,
})
const payment = ref(false)
const payment_history = ref(false)
const payments = ref()
const properties = ref()
const property_view = ref(false)
const client_lots = ref()
const isPrinted = ref(false)
const chart_view = ref(false)
const chartOfAccountRef = ref()
const printableRef = ref()
const infoSheetRef = ref()
const clientRef = ref()
const client_view = ref(false)
const infosheet_view = ref(false)
const clientInfo = ref()
const { getUserInfo } = getUser()
const checked = ref(false)
const filterText = ref()
const user = getUserInfo()
const clientPrint = ref()
const clientList = ref(props.clients)

const form = useForm({
    file: {},
    lot_id: 0,
    mode_of_payment: 'Over the counter',
    amount: 0,
    user_id: 0,
    status: 'Confirmed',
    date_of_payment: '',
    reference_number: ''
})

const reportsData = reactive({
    name: '',
    block: '',
    phase: '',
    lot_number: '',
    payments: {}
})


const payment_modes = ref([
    {
        payment: 'Gcash'
    },
    {
        payment: 'Bank Transfer'
    },
    {
        payment: 'Cash'
    }
])

defineOptions({layout: Layout})



const generateReport = (lot) => {
    chart_view.value = true
    property_view.value = false
    reportsData.name = lot.user.personal_info.first_name + ' ' + lot.user.personal_info.last_name
    reportsData.block = lot.block
    reportsData.phase = lot.property.phase
    reportsData.lot_number = lot.name
    reportsData.payments = lot.payment_plans
    reportsData.payments.monthly = lot.lot_group.monthly_amortizations
}
const checkPaymentHistory = (paymentsHistory) => {
    payments.value = paymentsHistory
    payment_history.value = true
}

const viewProperties = (lots) => {
    properties.value = lots
    property_view.value = true
}

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
      }).format(value);
}
const calculateTotalAmount = (lot_group) => {
    return lot_group.sqr_meter * lot_group.amount_per_sqr_meter
}

const makePayment = (client) => {
    form.user_id = client.id

    payment.value = true
    client_lots.value = client.lots
}

const submitPayment = () => {

    form.transform((data) => {

      return {
          ...data,

         date_of_payment: moment().format('YYYY-MM-DD')
      };
  }).post('/payments', {
      onSuccess: (() => {

          Swal.fire({
              title: "Success!",
              text: "Payment submitted successfully!",
              icon: "success"
          });
          router.get('/applications')
      })
  });
}

const calculateRemainingBalance = (lot) => {
    const total_amount = calculateTotalAmount(lot.lot_group)
    const total_payment = lot.payments.reduce((sum, payment) => sum + payment.amount, 0)

    return total_amount - total_payment
}



const { handlePrint } = useVueToPrint({
  content: printableRef,
  documentTitle: "AwesomeFileName",
});
const print = (printRef) => {
    if (printRef === 'chart-of-account') {
        printableRef.value = chartOfAccountRef.value
    }else if (printRef === 'info-sheet') {
        printableRef.value = infoSheetRef.value
    }else if(printRef === 'client-print') {
        printableRef.value = clientRef.value
    }
    handlePrint()
}

const handleClientInfo = (client) => {
    clientInfo.value = {
        first_name: client.personal_info.first_name,
        mi: '',
        last_name: client.personal_info.last_name,
        date_of_birth: client.personal_info.birth_day,
        age: calculateAge(client.personal_info.birth_day),
        occupation: client.work_details.job_title,
        address: 'Purok ' + client.address.purok + ', Barangay ' + client.address.barangay + ', ' + client.address.city + ' City ' + client.address.province,
        project_name: '',
        lots: client.lots,
    }
    infosheet_view.value = true
}
const calculateAge = (date) => {
    const birthDate = new Date(date);
    const currentDate = new Date();

    return currentDate.getFullYear() - birthDate.getFullYear();
}

const setMonthlyAmount = () => {
    const lot = client_lots.value.find(lot => lot.id === form.lot_id)

    form.amount = lot.lot_group.monthly_amortizations
}

const convertAddress = (lot) => {
    const transformedAddress = 'Phase ' + lot.property.phase + ', block ' + lot.block + ', ' + lot.name + ', Barangay ' + uppercase(lot.property.barangay) + ', ' + lot.property.city
    return transformAddress(transformedAddress)
}

const uppercase = (value) => {
     return value.toUpperCase()
}

const generateTodaysDate = () => {
    const today = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = today.toLocaleDateString('en-US', options);

    return formattedDate
}

const viewClientWithEightPercent  = () => {
    clientList.value = clientList.value.filter(client => client.is_eighty_percent === true);
}

const reset = () => {
    clientList.value = props.clients
}

const printClientWith80Percent = () => {
    clientPrint.value = props.clients.filter(client => client.is_eighty_percent === true);
    client_view.value = true
}
watch(filterText, (newVal, oldVal) => {
    if (!newVal) {
        clientList.value = props.clients
        return
    }
    axios.get('/filter-clients/' + newVal)
    .then(res => {
        console.log(res.data)
        clientList.value = res.data
    })
})
</script>

<template>
     <Dialog v-model:visible="client_view" modal :style="{ width: '60rem' }">
        <div ref="clientRef">
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
            <h1 class="text-center">CLIENTS THAT COMPLETES 80% PAYMENT</h1>
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
                            FIRST NAME
                        </th>
                        <th class="text-left">
                            LAST NAME
                        </th>
                        <th class="text-left">
                            PHONE NUMBER
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(client, i) in clientPrint" :key="i">
                        <td>{{ client.personal_info.first_name }}</td>
                        <td>{{ client.personal_info.last_name }}</td>
                        <td>{{ client.personal_info.phone_number }}</td>

                    </tr>
                </tbody>
            </v-table>
            <br>
            <span class="mt-4">THIS IS A SYSTEM GENERATED REPORT</span>

        </div>
        <button class="btn btn-block btn-info" @click="print('client-print')">Print</button>

    </Dialog>
    <Dialog v-model:visible="chart_view" modal :style="{ width: '60rem' }">
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
                <tbody>
                    <tr>

                        <td>
                            NAME: {{ reportsData.name }}
                        </td>
                        <td>
                            PHASE: {{ reportsData.phase }}
                        </td>
                        <td>
                            BLOCK: {{ reportsData.block }}
                        </td>
                        <td>
                            LOT: {{ reportsData.lot_number }}
                        </td>
                    </tr>
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
                           DUE DATE
                        </th>
                        <th class="text-left">
                            MONTHLY AMOUNT
                        </th>
                        <th class="text-left">
                            DATE PAID
                        </th>
                        <th class="text-left">
                            AMOUNT PAID
                        </th>
                        <th class="text-left">
                            O.R / A.R. NO
                        </th>

                        <th class="text-left">
                            PAYMENT MODE
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(payment, i) in reportsData.payments" :key="i">
                        <td>{{ payment.due_date }}</td>
                        <td>{{ formatCurrency(payment.lot.lot_group.monthly_amortizations) }}</td>
                        <td>{{ payment.payment?.date_of_payment }}</td>
                        <td>
                            <span v-if="payment.payment?.amount">{{ formatCurrency(payment.payment.amount) }}</span>
                        </td>
                        <td>
                            {{ payment.payment?.invoice_number }}
                        </td>
                        <td>
                            {{ payment.payment?.mode_of_payment }}
                        </td>
                    </tr>
                </tbody>
            </v-table>
            <br>
            <span class="mt-4">THIS IS A SYSTEM GENERATED REPORT</span>

        </div>
        <button class="btn btn-block btn-info" @click="print('chart-of-account')">Print</button>

    </Dialog>

    <Dialog v-model:visible="payment" modal header="Make Payment" :style="{ width: '50rem' }">
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Lot</label>
            <select  class="js-basic-single form-control" name="lot" v-model="form.lot_id" @change="setMonthlyAmount">
                <option  v-for="lot in client_lots" :value="lot.id" :key="lot.id">{{ convertAddress(lot) }}</option>
            </select>
        </div>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Mode of Payment</label>
            <select  class="js-basic-single form-control" name="lot" v-model="form.mode_of_payment">
                <option  v-for="payment in payment_modes" :value="payment.payment" :key="payment.payment">{{ payment.payment }}</option>
            </select>
        </div>

        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Amount</label>
            <InputText class="tw-w-full tw-rounded-md" disabled v-model="form.amount"/>

        </div>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4" v-if="form.mode_of_payment != 'Cash'">
            <label for="username" class="tw-font-semibold tw-w-24">Reference Number</label>
            <InputText class="tw-w-full tw-rounded-md" v-model="form.reference_number"/>

        </div>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Invoice Number</label>
            <InputText class="tw-w-full tw-rounded-md" v-model="form.invoice_number"/>

        </div>
        <FileUpload :file="form.file" />
        <div class="tw-flex tw-justify-end tw-gap-2">
            <Button type="button" label="Save" @click="submitPayment"></Button>
        </div>
    </Dialog>
    <Dialog v-model:visible="payment_history" modal header="Payment Plan" :style="{ width: '100rem' }">

        <v-table>
            <thead>
            <tr>
                <th class="text-left">
                   Payment For
                </th>
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
                <tr v-for="payment in payments" :key="payment.id">
                    <td>
                        Phase {{ payment.plan[0].lot.property.phase }},
                        Block {{ payment.plan[0].lot.block }},
                        Purok {{ payment.plan[0].lot.property.purok }},
                        Barangay {{ payment.plan[0].lot.property.barangay }},
                        {{ payment.plan[0].lot.property.city }} City,
                        {{ payment.plan[0].lot.property.province }}
                    </td>
                    <td>
                        {{ payment.plan?.due_date }}
                    </td>
                    <td>
                        {{ formatCurrency(payment.amount) }}
                    </td>
                    <td>
                        {{ payment.date_of_payment }}

                    </td>
                    <td>
                        {{ payment.mode_of_payment }}
                    </td>
                    <td>
                        {{ payment.status }}
                    </td>
                    <td>
                       {{ payment.invoice_number }}

                    </td>

                </tr>
            </tbody>
        </v-table>
    </Dialog>

    <Dialog v-model:visible="property_view" modal header="Client Properties" :style="{ width: '100rem' }">
        <v-table>
            <thead>
            <tr>
                <th class="text-left">
                   Address
                </th>

                <th class="text-left">
                    Size
                </th>


                <th class="text-left">
                   Amount Per Square Meters
                </th>

                <th class="text-left">
                    Total Amount
                </th>

                <th class="text-left">
                    Remaining Balance
                </th>
                <th class="text-left">
                    Payment Percentage
                </th>
                <th class="text-left">
                    Action
                </th>

            </tr>
            </thead>
            <tbody>
                <tr v-for="lot in properties" :key="lot.id">
                    <td>
                        Phase {{ lot.property.phase }},
                        Block {{ lot.block }},
                        Purok {{ lot.property.purok }},
                        Barangay {{ lot.property.barangay }},<br>
                        {{ lot.property.city }} City,
                        {{ lot.property.province }}
                    </td>

                    <td> {{ lot.lot_group.sqr_meter }} Square Meter</td>
                    <td>
                        {{ formatCurrency(lot.lot_group.amount_per_sqr_meter) }}
                    </td>
                    <td>
                        {{ formatCurrency((lot.lot_group.amount_per_sqr_meter * lot.lot_group.sqr_meter)) }}

                    </td>
                    <td>
                       {{ formatCurrency(calculateRemainingBalance(lot)) }}
                    </td>
                    <td>
                        <ProgressBar :value="lot.payment_percentage" />
                    </td>
                    <td>
                        <a  @click.prevent="generateReport(lot)" href="javascript:void(0);" class="tooltip-wrapper btn btn-icon btn-round btn-light" data-toggle="tooltip" data-placement="top" title="" data-original-title="Generate Chart Of Accounts"><i class="fas fa-print"></i></a>
                    </td>


                </tr>
            </tbody>
        </v-table>
    </Dialog>
    <Dialog v-model:visible="infosheet_view" modal header="Client Information Sheet" :style="{ width: '100rem' }">
        <div ref="infoSheetRef">
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
            <h1 class="text-center">BUYER'S INFORMATION SHEET</h1>
            <v-table class="border-none">
                <tbody>
                    <tr>

                        <td colspan="3">
                            PREPARED BY: {{ user.personal_info.first_name }} {{  user.personal_info.last_name }}
                        </td>
                        <td colspan="3">
                            GENERATED ON: {{ generateTodaysDate() }}
                        </td>
                        </tr>
                    <tr>
                        <td>
                            FIRSTNAME: {{ clientInfo.first_name }}
                        </td>
                        <td>
                            MI: {{ clientInfo.middle_name }}
                        </td>
                        <td colspan="4">
                            SURNAME: {{ clientInfo.last_name }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">DATE OF BIRTH: {{ clientInfo.date_of_birth }}</td>

                    </tr>
                    <tr>
                        <td colspan="6">AGE: {{ clientInfo.age }}</td>
                    </tr>
                    <tr>
                        <td colspan="6">OCCUPATION: {{ clientInfo.occupation }}</td>
                    </tr>
                    <tr>
                        <td>CURRENT ADDRESS:</td>
                        <td>PRESENT ADDRESS:</td>
                        <td colspan="5">PERMANENT ADDRESS: {{ clientInfo.address }}</td>
                    </tr>
                    <tr>
                        <td colspan="6">PROJECT NAME:</td>
                    </tr>
                    <tr v-for="(lot, i) in clientInfo.lots">
                        <td>BLOCK NO: {{ lot.block }}</td>
                        <td>LOT NO: {{  lot.name }}</td>
                        <td>SQM NO: {{ lot.lot_group.sqr_meter }}</td>
                        <td>MONTHLY PAYMENT: {{ formatCurrency(lot.lot_group.monthly_amortizations) }}</td>
                        <td>TCP: {{ formatCurrency((lot.lot_group.sqr_meter * lot.lot_group.amount_per_sqr_meter)) }}</td>
                        <td colspan="2">TERMS OF PAYMENT: {{ lot.property.balance_payable }} Month(s)</td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <p>
                                I HEREBY declare that the above statement is TRUE AND CORRECT, I agree to inform the owner if my reservation of
                                the said lots listed above will no be paid until full within 3 days from the time of reservation period. This reservation
                                sgall be automatically cancelled and forfiteid. If the buyer wishes to discontinue the said installment all previous payments
                                are non-refundable and subject for forfieture.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            BUYERS SIGNATURE/DATE RECEIVED
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                           SALES DIRECTOR <Input style="border:0;"/>
                        </td>
                        <td colspan="2">
                           AGENT NAME <Input style="border:0;"/>
                        </td>
                        <td colspan="2">
                           AGENT NAME <Input style="border:0;"/>
                        </td>
                    </tr>
                </tbody>
            </v-table>
            <span class="text-center">THIS IS A SYSTEM GENERATED REPORT</span>

        </div>
        <button class="btn btn-block btn-info" @click="print('info-sheet')">Print</button>
    </Dialog>
    <div class="row" >
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    <h1>Clients</h1>
                </div>

                <div class="ml-auto d-flex align-items-center">

                    <nav>
                        <ol class="breadcrumb p-0 m-b-0">
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/dashboard')"><i class="ti ti-home"></i></a>
                            </li>

                            <li class="breadcrumb-item active text-primary" aria-current="page">Clients</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-statistics clients-contant">
                <div class="card-header">
                    <div class="d-xxs-flex justify-content-between align-items-center">
                        <div class="card-heading">
                            <h5 class="card-title">Client Lists</h5>
                        </div>

                        <div class="card flex justify-content-center">
                            <div class="dropdown">
                                <a class="p-2" href="#!" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fe fe-settings"></i>
                                </a>
                                <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
                                    <h6 class="mb-1">Action</h6>
                                    <a @click.prevent="reset" class="dropdown-item" href="#!"><i class="fa-fw far fa-undo pr-2"></i>Reset Filter</a>
                                    <a @click.prevent="viewClientWithEightPercent" class="dropdown-item" href="#!"><i class="fa-fw far fa-buffer pr-2"></i>View Clients with eighty percent payment</a>
                                    <a @click.prevent="printClientWith80Percent" class="dropdown-item" href="#!"><i class=" pr-2"></i>Print Clients with eighty percent payment</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body table-responsive">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <InputText placeholder="...Search" v-model="filterText" class="tw-w-[300px] float-right" />
                        </div>
                    </div>
                    <table class="table mb-0 table-border-3">
                        <thead>
                            <tr>
                                <th class="text-left">
                                    Name
                                </th>
                                <th class="text-left">
                                    Email
                                </th>
                                <th class="text-left">
                                    Phone Number
                                </th>
                                <th class="text-left">
                                    Gross Monthly Income
                                </th>
                                <th class="text-left">
                                    Completed 80% Payment
                                </th>

                                <th class="text-left">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr v-for="client in clientList" :key="client.id">
                                <td>
                                    <div class="">
                                        <div class="avatar avatar-lg mr-2">
                                            <Avatar :name="client.personal_info?.first_name" />
                                            <!-- <img src="assets/img/avatar/01.jpg" class="img-fluid avatar-img rounded-circle" alt="Clients-01"> -->
                                        </div>
                                        <p class="font-weight-bold text-dark">{{ client.personal_info?.first_name }} {{ client.personal_info?.last_name }}</p>
                                    </div>
                                </td>
                                <td> {{ client.email }}</td>
                                <td>{{ client.personal_info?.phone_number }}</td>
                                <td>
                                    {{ formatCurrency(client.work_details?.gross_monthly_income) }}
                                </td>
                                <td>
                                    <span class="mr-2 mb-2 mr-sm-0 mb-sm-0 badge badge-success" v-if="client.is_eighty_percent">Yes</span>
                                    <span class="mr-2 mb-2 mr-sm-0 mb-sm-0 badge badge-warning" v-else>No</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                            <a class="p-2" href="#!" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-settings"></i>
                                            </a>
                                            <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
                                                <h6 class="mb-1">Action</h6>
                                                <!-- <a @click.prevent="checkPaymentHistory(client.payments)" class="dropdown-item" href="#!"><i class="fa-fw far fa-file-alt pr-2"></i>View Payment History</a> -->
                                                <a @click.prevent="viewProperties(client.lots)" class="dropdown-item" href="#!"><i class="fa-fw far fa-file-alt pr-2"></i>View Properties</a>
                                                <!-- <a class="dropdown-item" href="#!"><i class="fa-fw fas fa-trash pr-2"></i>Delete</a>  -->
                                                <a @click.prevent="makePayment(client)" class="dropdown-item" href="#!"><i class="fa-fw fas fa-receipt pr-2"></i>Make Payment</a>
                                                <a @click.prevent="handleClientInfo(client)" class="dropdown-item" href="#!"><i class="fa-fw fas fa-receipt pr-2"></i>View Information Sheet</a>
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
