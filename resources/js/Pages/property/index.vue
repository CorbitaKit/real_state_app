<script setup>
import Layout from '../layout/main.vue'
import { router, useForm } from '@inertiajs/vue3'
import LandMark from './components/landmark.vue'
import Swal from 'sweetalert2'
import {getUser} from '../plugins/get-user-plugin'
import FileUpload from '../components/fileupload.vue'
import { reactive, ref } from 'vue'
const { getUserInfo } = getUser()
const user = getUserInfo()
const visible = ref(false)
import { useVueToPrint } from "vue-to-print";
const props = defineProps({
    properties: Object
})

const transactionRef = ref()
const transaction_view = ref(false)
const transaction = reactive({
    phase: '',
    months: '',
    transactions: []
})
const form = useForm({
    file: {},
    property_id: ''
})
const submit = () => {

    router.get('/properties/create')
}

const home = () => {
    router.get('/dashboard')
}



const { handlePrint } = useVueToPrint({
  content: transactionRef,
  documentTitle: "AwesomeFileName",
});

const setLocation = (property) => {
    return property.purok + ', ' + property.barangay + ', ' + property.city
}
const show = (property_id) => {
    router.get('/properties/' + property_id)
}

const deleteProperty = (property_id) => {
    Swal.fire({
        title: "Are you sure you want to delete this property?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
    if (result.isConfirmed) {
        router.delete('/properties/' + property_id)
        Swal.fire({
            title: "Deleted!",
            text: "Property has been deleted.",
            icon: "success"
        });
    }
});
}

const uploadPic = (id) => {
    form.property_id = id
    visible.value = true
}

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
      }).format(value);
}


const setTransaction = (property) => {
    transaction_view.value = true
    transaction.phase = property.phase
    transaction.months = property.balance_payable
    transaction.transactions = property.transactions
}
const uploadPropertyMap = () => {
    form.post('/upload-property-map', {
        onSuccess: (() => {
            Swal.fire({
                title: "Uploaded!",
                text: "Map Uploaded Successfully!.",
                icon: "success"
            });
        })
    })
}
defineOptions({layout: Layout})
</script>


<template>
    <!-- <Header @submit="submit" :title="'Property Page'" :displayBtn="setButton()" :btnTxt="'Create Property'" />
    <div class="grid grid-cols-3 gap-4 mt-5">
       <Card v-for="property in properties" :key="property.id" :property="property" :displayBtn="true"/>
    </div> -->

    <Dialog v-model:visible="transaction_view" modal :style="{ width: '60rem' }">
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
                            PHASE: {{ transaction.phase }}
                        </td>
                        <td>
                            MONTHS: {{ transaction.months }} Month(s)
                        </td>
                    </tr>
                </tbody>
            </v-table>
            <v-table>
                <thead>
                <tr>
                    <th class="text-left">
                        Name
                    </th>
                    <th class="text-left">
                        TCP
                    </th>


                    <th class="text-left">
                        Total Payment
                    </th>

                    <th class="text-left">
                        Balance
                    </th>



                </tr>
                </thead>
                <tbody>
                    <tr v-for="transaction in transaction.transactions">
                    <td>
                        {{ transaction.user.personal_info.first_name }}
                        {{ transaction.user.personal_info.last_name }}

                    </td>
                    <td>{{ formatCurrency(transaction.tcp) }}</td>
                    <td>{{ formatCurrency(transaction.total_amount) }}</td>
                    <td>{{ formatCurrency(transaction.balance) }}</td>
                    </tr>
                </tbody>
            </v-table>
        </div>
        <button class="btn btn-block btn-info" @click="handlePrint">Print</button>
    </Dialog>

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
                                <a href="#" @click="home"><i class="ti ti-home"></i></a>
                            </li>

                            <li class="breadcrumb-item active text-primary" aria-current="page">Properties</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
    <Dialog v-model:visible="visible" modal header="Upload Property Map" :style="{ width: '50rem' }">
        <FileUpload :file="form.file" />
        <div class="tw-flex tw-justify-end tw-gap-2">
            <Button type="button" label="Save" @click="uploadPropertyMap"></Button>
        </div>
    </Dialog>
    <div class="row">
        <div class="col-lg-6" v-for="property in properties" :key="property.id">
            <div class="card card-statistics">
                <div class="card-header d-flex justify-content-between tw-text-xl tw-font-weight-bold">
                    <div class="card-heading">
                        <h5 class="card-title">
                            Phase {{ property.phase }},
                            Purok {{ property.purok }},
                            Barangay {{ property.barangay }},
                            {{ property.city }} City,
                            {{ property.region }}

                        </h5>
                        <h4>{{ property.description }}</h4>
                    </div>
                    <div>
                        <a v-if="user.role.name !== 'Client'" @click.prevent="uploadPic(property.id)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Upload Property Map" href="javascript:void(0);" class="btn btn-xs btn-icon btn-round btn-outline-info"><i class="fas fa-upload"></i></a>
                        <a v-if="user.role.name !== 'Client'" @click.prevent="setTransaction(property)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Print Transaction Records   " href="javascript:void(0);" class="btn btn-xs btn-icon btn-round btn-outline-info ml-1"><i class="fas fa-file"></i></a>
                        <a @click.prevent="show(property.id)" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Property Details" href="javascript:void(0);" class="btn btn-xs btn-icon btn-round btn-outline-info ml-1"><i class="fas fa-eye"></i></a>
                        <a v-if="user.role.name !== 'Client'" @click.prevent="deleteProperty(property.id)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Property" href="javascript:void(0);" class="btn btn-xs btn-icon btn-round btn-outline-danger ml-1"><i class="fas fa-trash"></i></a>

                    </div>
                </div>
                <div class="card-body">

                        <LandMark :address="setLocation(property)" :key="property.id" :map="property.id" :property="property"/>

                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>
.custom-dropdown {
    position: absolute; /* Ensures the dropdown positions relative to the button */
    right: 0; /* Align to the right edge of the parent container */
    top: 100%; /* Position below the button */
}
</style>
