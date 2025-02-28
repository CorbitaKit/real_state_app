<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import FileUpload from '../components/fileupload.vue'
import { router, useForm } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import moment from 'moment'
import Swal from 'sweetalert2'
import { useVueToPrint } from "vue-to-print";

const props = defineProps({
    clients: Object
})
const payment = ref(false)
const payment_history = ref(false)
const payments = ref()
const properties = ref()
const property_view = ref(false)
const client_lots = ref()
const chart_view = ref(false)
const chartOfAccountRef = ref()
const printableRef = ref()
const infoSheetRef = ref()
const infosheet_view = ref(false)
const clientInfo = ref()
const form = useForm({
    file: {},
    lot_id: 0,
    mode_of_payment: 'Over the counter',
    amount: 0,
    user_id: 0,
    status: 'Pending',
    date_of_payment: ''
})

const reportsData = reactive({
    name: '',
    block: '',
    phase: '',
    lot_number: '',
    payments: {}
})
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
    form.user = client.id

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
</script>

<template>
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
        </div>
        <button class="btn btn-block btn-info" @click="print('chart-of-account')">Print</button>
    </Dialog>

    <Dialog v-model:visible="payment" modal header="Make Payment" :style="{ width: '50rem' }">
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Lot</label>
            <select  class="js-basic-single form-control" name="lot" v-model="form.lot_id" @change="setMonthlyAmount">
                <option  v-for="lot in client_lots" :value="lot.id" :key="lot.id">{{ lot.name }}</option>
            </select>
        </div>

        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Amount</label>
            <InputText class="tw-w-full tw-rounded-md" v-model="form.amount"/>

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
                        <td>
                            FIRSTNAME: {{ clientInfo.first_name }}
                        </td>
                        <td>
                            MI:
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
                           SALES DIRECTOR
                        </td>
                        <td colspan="2">
                           AGENT NAME
                        </td>
                        <td colspan="2">
                           AGENT NAME
                        </td>
                    </tr>
                </tbody>
            </v-table>

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

                    </div>
                </div>
                <div class="card-body table-responsive">
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
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr v-for="client in clients" :key="client.id">
                                <td>
                                    <div class="">
                                        <div class="avatar avatar-lg mr-2">
                                            <img src="assets/img/avatar/01.jpg" class="img-fluid avatar-img rounded-circle" alt="Clients-01">
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
