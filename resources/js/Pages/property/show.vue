<script setup>
import Layout from '../layout/main.vue'
import {getUser} from '../plugins/get-user-plugin'
import { useConfirm } from "primevue/useconfirm";
import { useForm, router } from '@inertiajs/vue3';
import moment from 'moment';
import { useToaster } from '../composables/toast'
import { reactive, ref } from 'vue';
import Swal from 'sweetalert2'
import { toWords } from 'number-to-words';
import { useVueToPrint } from "vue-to-print";
import axios from 'axios'
import { FilterMatchMode } from 'primevue/api';
const { show } = useToaster()
const { getUserInfo } = getUser()
const confirm = useConfirm();

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    status: { value: null, matchMode: FilterMatchMode.EQUALS },
});

const user = getUserInfo()
const visible = ref(false)
const payments = ref()
const payment_view = ref(false)
const admin_apply = ref(false)
const is_contract = ref(false)
const transactionRef = ref()
const terms = ref(false)
const contractRef = ref()
const agree = ref(false)
const filter = ref()
const printableRef = ref()
const contract_data = reactive({
    seller: '',
    seller_address: '',
    buyer: '',
    buyer_address: '',
    lot_address: '',
    size: '',
    property_address:'',
    amount_in_word: '',
    amount: '',
})
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

const { handlePrint } = useVueToPrint({
  content: printableRef,
  documentTitle: "AwesomeFileName",
});

const print = (printRef) => {
    if (printRef === 'contract') {
        printableRef.value = contractRef.value
    } else if (printRef === 'payment-history') {
        printableRef.value = transactionRef.value
    }

    handlePrint()
}

const checkUser = (lot) => {
    if (user.role.name === 'Client') {
        applyForLot(lot)
    } else {
        form.lot_id = lot.id
        admin_apply.value = true

    }
}

const applyForLot = (lot) => {


        // confirm.require({
        //     message: 'Are you sure you want to apply for this lot?',
        //     header: 'Confirmation',
        //     icon: 'pi pi-exclamation-triangle',
        //     rejectProps: {
        //         label: 'Cancel',
        //         severity: 'secondary',
        //         outlined: true
        //     },
        //     acceptProps: {
        //         label: 'Save'
        //     },
        //     accept: () => {
        //         form.lot_id = lot.id
        //         sendApplication()
        //     },
        // });
        Swal.fire({
            title: "Are you sure?",
            text: "You want to apply for this lot?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes!"
            }).then((result) => {
            if (result.isConfirmed) {
                form.lot_id = lot.id
                // sendApplication()
                terms.value = true
            }
            });

        }
const siteVisit = () => {
    form.application_type = 'Site Visit',
    form.reserved_date = moment(form.reserved_date).format('YYYY-MM-DD')
    form.lot_id = props.property.lots[0].id
    sendApplication()
    visible.value = false
}
const sendApplication = () => {
    terms.value = false
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

const processContract = (lot) => {
    contract_data.seller = user.personal_info.first_name + ' ' + user.personal_info.last_name
    contract_data.buyer = lot.user.personal_info.first_name + ' ' + lot.user.personal_info.last_name
    contract_data.buyer_address = lot.user.personal_info.address
    contract_data.property_address = 'Purok ' + lot.property.purok + ', Barangay of ' + lot.property.barangay + ', ' + lot.property.city + ', ' + lot.property.province
    contract_data.size = lot.lot_group.sqr_meter + ' square meters'
    contract_data.lot_address = 'Phase ' + lot.property.phase + ', Block ' + lot.block + ', ' + lot.name
    contract_data.amount_in_word = toWords((lot.lot_group.sqr_meter * lot.lot_group.amount_per_sqr_meter))
    contract_data.amount = formatCurrency((lot.lot_group.sqr_meter * lot.lot_group.amount_per_sqr_meter))
    is_contract.value = true

}

const isAlreadyPaid80Percent = (lot) => {
    return  calculatePercentage(lot) >= 80
}

const notifyClient = (user_id) => {
    axios.get('/notify-user/' + user_id)
    .then(response=> {
        Swal.fire({
            title: "Success!",
            text: "SMS sent succesfully!",
            icon: "success"
        });
    }).catch(err => {
        console.log(err)
    })
}

const generateTodaysDate = () => {
    const today = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = today.toLocaleDateString('en-US', options);

    return formattedDate
}


defineOptions({layout: Layout})
</script>

<template>
    <div class="row">
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    <h1>Property Lots Information</h1>
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

                            <li class="breadcrumb-item active text-primary" aria-current="page">Property Lots</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
    <Dialog v-model:visible="terms" modal :style="{ width: '50rem' }">


                <div class="card-header">
                    <h5 class="card-title">Terms and Conditions</h5>
                </div>
                <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                    <p>
                        I HEREBY declare that the above statement is TRUE AND CORRECT, I agree to inform the owner if my reservation of the said Lots listed above will not be paid in full within 3 days from the time of reservation period, this reservation shall be automatically cancelled and forfeited.

If the buyer wishes to discontinue the said installment all previous payments are non-refundable and subject for forfeiture.
                    </p>
                </div>


            <!-- Checkbox for Agreement -->
            <div class="form-check mb-3">
                <input class="form-check-input" v-model="agree" type="checkbox" id="termsCheckbox" required>
                <label class="form-check-label" for="termsCheckbox">
                    I agree to the Terms and Conditions
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" @click="sendApplication" class="btn btn-primary" :disabled="!agree" id="submitBtn">Submit</button>

    </Dialog>
    <Dialog v-model:visible="payment_view" modal header="Payment History" :style="{ width: '50rem' }">
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
            <h1 class="text-center">Transaction Records</h1>
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
        </div>
        <button class="btn btn-block btn-info" @click="print('payment-history')">Print</button>
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


    <div class="row" v-if="!is_contract">
        <div class="col-xl-12">
            <div class="card card-statistics border-0 shadow-none mb-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <input type="text" v-model="filter" class="form-control mb-2 tw-w-[300px] float-right" placeholder="...Search"/>
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
                                <tr v-for="lot in property.lots.filter(lot => !(user.role.name === 'Client' && lot.status === 'Occupied'))" :key="lot.id">
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
                                    <td>
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
                                                <i class="fe fe-settings"></i>
                                            </a>
                                            <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
                                                <h6 class="mb-1">Action</h6>
                                                <a v-if="user.role.name !== 'Client'" @click="showPayments(lot.payments)" class="dropdown-item" href="#"><i class="fa-fw far fa-file-alt pr-2"></i>View Payment History</a>
                                                <a v-if="user.role.name !== 'Client' && lot.status === 'Occupied'" @click="processContract(lot)" class="dropdown-item" href="#"><i class="fa-fw far fa-file-alt pr-2"></i>Print Contract</a>
                                                <a v-if="lot.status === 'Available'" @click.prevent="checkUser(lot)" class="dropdown-item" href="#!"><i class="fa-fw far fa-file-pdf pr-2"></i>Apply</a>
                                                <a v-if="user.role.name === 'Client'" @click.prevent="visible = true" class="dropdown-item" href="#!"><i class="fa-fw far fa-calendar pr-2"></i>Request a site visit</a>
                                                <a v-if="user.role.name != 'Client' && isAlreadyPaid80Percent(lot)" @click.prevent="notifyClient(lot.user_id)" class="dropdown-item" href="#!"><i class="fa-fw far fa-bell pr-2"></i>Notify Client</a>

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
    <div class="row" v-if="is_contract">

        <div class="col-xl-12 col-sm-12" style="color:black;" ref="contractRef">

            <div class="card card-statistics">
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

                <div class="card-body p-5" id="printMe" style="font-family: 'Times New Roman', serif; line-height: 1.5; padding: 20px; max-width: 800px; margin: 0 auto;">
    <h1>
        <p class="MsoNormal" align="center" style="text-align:center; margin: 0; padding: 0;">
            <b><span style="font-size:18pt;">CONTRACT TO SELL</span></b>
        </p>
        <br>
        <p class="MsoNormal" style="text-align: justify; margin-bottom: 1em;">
            <span style="font-size:14pt;">KNOW ALL MEN BY THESE PRESENTS:</span>
        </p>

        <p class="MsoNormal" style="text-align: justify; margin-bottom: 1em;">
            <span style="font-size:14pt;">This contract to sell is made, executed, and entered into by and between:</span>
        </p>

        <p class="MsoNormal" style="text-align: justify; margin-bottom: 1em;">
            <span style="font-size:14pt;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b>{{ contract_data.seller }}</b>, of legal age, Filipino, single, and a resident of Panabo City, Davao del Norte, hereinafter referred to as the <b>SELLER</b>.
            </span>
        </p>

        <p class="MsoNormal" align="center" style="text-align:center; margin-bottom: 1em;">
            <b><span style="font-size:14pt;">-AND-</span></b>
        </p>

        <p class="MsoNormal" style="text-align: justify; margin-bottom: 1em;">
            <span style="font-size:14pt;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b>{{ contract_data.buyer }}</b>, of legal age, Filipino, <b>married</b>, and a resident of <b>{{ contract_data.buyer_address }}</b>, hereinafter referred to as the <b>BUYER</b>.
            </span>
        </p>

        <p class="MsoNormal" align="center" style="text-align:center; margin-bottom: 1em;">
            <b><span style="font-size:14pt;">-WITNESSETH-</span></b>
        </p>

        <p class="MsoNormal" style="text-align: justify; margin-bottom: 1em;">
            <b><span style="font-size:14pt;">WHEREAS</span></b>
            <span style="font-size:14pt;">
                the <b>SELLER</b> is authorized to enter into a contract to sell involving a parcel of land located at <b>{{ contract_data.property_address }}</b>, and covered by <b>Transfer Certificate of Title No. 256092</b>, containing a total area of <b>Fifty thousand square meters</b>, more or less, issued by the Registry of Deeds of the province of Davao del Norte.
            </span>
        </p>

        <p class="MsoNormal" style="text-align: justify; margin-bottom: 1em;">
            <span style="font-size:14pt;">
                Whereas, the <b>BUYER</b> has offered to buy a portion of said parcel of land containing an area of <b>{{ contract_data.size }}</b>, more or less, identified as <b>{{ contract_data.lot_address }}</b>, of the aforesaid property, and the <b>SELLER</b> has agreed to sell the above-mentioned property under the terms and conditions herein below set forth:
            </span>
        </p>

        <p class="MsoNormal" style="text-align: justify; margin-bottom: 1em;">
            <span style="font-size:14pt;">
                NOW THEREFORE, for and in consideration of the total sum of <b>{{ contract_data.amount_in_word }} ({{ contract_data.amount }})</b> Philippine currency, and of the covenants herein after set forth, the <b>SELLER</b> agrees to sell, and the <b>BUYER</b> agrees to buy the aforesaid parcel of land, being a portion of the parcel of land covered by <b>Transfer Certificate of Title No. 256092</b>, subject to the following terms:
            </span>
        </p>

        <br>

        <p class="MsoNormal" align="center" style="text-align:center;">
            <b>----------------------------------------</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <b>---------------------------------------</b>
            <br>
            <span style="font-size:14pt;">SELLER</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="font-size:14pt;">BUYER</span>
        </p>
        <br><br>
        <p class="MsoNormal" align="center" style="text-align:center;">
            <b>----------------------------------------</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <b>---------------------------------------</b>
            <br>
            <span style="font-size:14pt;">WITNESS 1</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="font-size:14pt;">WINTESS 2</span>
        </p>
    </h1>
</div>



    </div>
</div>
            <a @click="print('contract')" href="javascript:void(0);" class="btn btn-block btn-round btn-outline-info">Print</a>
        </div>

</template>
