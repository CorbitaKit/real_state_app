
<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import {getUser} from '../plugins/get-user-plugin'
import { useForm } from '@inertiajs/vue3'
import { useConfirm } from "primevue/useconfirm";
import { ref } from 'vue';
import { useToaster } from '../composables/toast'
import Swal from 'sweetalert2'


const { getUserInfo } = getUser()
const confirm = useConfirm();
const { show } = useToaster()
const client_view = ref(false)

const props = defineProps({
    applications: Object
})
const user = getUserInfo()
const visible = ref(false)
const application_id = ref()
const client = ref()
const form = useForm({
    status: '',
    reason: ''
})

const handleStatusChange = (status, id) => {
    application_id.value = id
    if (status === 'Approved') {
        confirm.require({
            message: 'Are you sure you want to approve this application?',
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
                form.status = status
                sendRequest()
            },
        });
    } else {
        visible.value = true
    }
}

const rejectApplication = () => {
    form.status = 'Rejected'
    sendRequest()
    visible.value = false
}
const sendRequest = () => {
    form.patch('/applications/' + application_id.value, {
        onSuccess: (() => {
            Swal.fire({
                title: "Success!",
                text: "Application update!, Email sent to the client",
                icon: "success"
            });
        })
    })
}

const setClient = (selectedClient) => {
    client.value = selectedClient
    client_view.value = true
}
defineOptions({layout: Layout})

</script>

<template>
    <div class="row">
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    <h1>Applications</h1>
                </div>

                <div class="ml-auto d-flex align-items-center">

                    <nav>
                        <ol class="breadcrumb p-0 m-b-0">
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/dashboard')"><i class="ti ti-home"></i></a>
                            </li>

                            <li class="breadcrumb-item active text-primary" aria-current="page">Applications</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
    <ConfirmDialog />
    <Dialog v-model:visible="visible" modal header="Reject Application" :style="{ width: '50rem' }">
        <span class="tw-text-surface-500 dark:tw-text-surface-400 tw-block tw-mb-8">State your reason for rejecting this application.</span>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
            <label for="username" class="font-semibold w-24">Reason</label>
            <InputText v-model="form.reason" class="tw-w-full tw-rounded-md"/>
        </div>
        <div class="tw-flex tw-justify-end tw-gap-2">
            <Button type="button" label="Save" @click="rejectApplication"></Button>
        </div>
    </Dialog>
    <Dialog v-model:visible="client_view" modal header="Client Details" :style="{ width: '90rem' }">
        <div class="card card-statistics contact-contant">
            <div class="card-body py-4">

                <div class="table-responsive mt-2">
                        <table class="table mb-0 table-border-3">
                            <tbody class="mb-0">
                                <tr>
                                  <td>
                                    Name
                                  </td>
                                  <td>
                                    {{ client.personal_info.first_name }}
                                    {{ client.personal_info.last_name }}

                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Phone Number
                                  </td>
                                  <td>
                                    {{ client.personal_info.phone_number }}

                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Address
                                  </td>
                                  <td>
                                    Purok {{ client.address.purok }},
                                    Barangay {{ client.address.barangay }},
                                    {{ client.address.barangay }},
                                     {{ client.address.province }}
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Company Name
                                  </td>
                                  <td>
                                    {{ client.work_details.company_name}}
                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Company Phone Number
                                  </td>
                                  <td>
                                    {{ client.work_details.company_number}}

                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Company Email
                                  </td>
                                  <td>
                                    {{ client.work_details.company_email}}

                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Job Title
                                  </td>
                                  <td>
                                    {{ client.work_details.job_title }}

                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Position
                                  </td>
                                  <td>
                                    {{ client.work_details.position }}

                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Length Of Service
                                  </td>
                                  <td>
                                    <span v-if="client.work_details.length_of_stay_month">{{ client.work_details.length_of_stay_month }} Month(s)</span>
                                    <span v-if="client.work_details.length_of_stay_year">{{ client.work_details.length_of_stay_year }} Year(s)</span>

                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Employment Status
                                  </td>
                                  <td>
                                    {{ client.work_details.status }}

                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Gross Monthly Income
                                  </td>
                                  <td>
                                    {{ client.work_details.gross_monthly_income }}

                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    Requirements
                                  </td>
                                  <td>
                                    <Image alt="Image" preview v-if="client.files.length > 0">
                                        <template #previewicon>
                                            <i class="pi pi-search"></i>
                                        </template>
                                        <template #image>
                                            <img :src="'/storage/app/public/'+client.files[0]?.url" alt="image" class="tw-h-[50px]"/>
                                        </template>
                                        <template #preview="slotProps">
                                            <img :src="'/storage/app/public/'+client.files[0]?.url" alt="preview" :style="slotProps.style" @click="slotProps.onClick" />
                                        </template>
                                    </Image>
                                  </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
            </div>
        </div>
    </Dialog>
    <!-- <div class="relative overflow-x-auto mt-5">
        <v-table>
            <thead>
                <tr>
                    <th v-if="user.role_id != 3" scope="col" class="px-6 py-3">
                       Applicant
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Application Type
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Reserved Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Lot Address
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th v-if="user.role_id != 3" scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="application in applications" :key="application.id">
                    <th v-if="user.role_id != 3" scope="row" >
                        <a class="text-blue" style="color:blue;" :href="`/users/${application.user.id}`">{{ application.user.personal_info.first_name }} {{ application.user.personal_info.last_name }} </a>
                    </th>
                    <th scope="row">
                        {{ application.application_type }}
                    </th>
                    <td class="px-6 py-4">
                        {{ application.reserved_date }}
                    </td>
                    <td class="px-6 py-4">
                        Phase {{ application.lot.property.phase }},
                        purok {{ application.lot.property.purok }},
                        barangay {{ application.lot.property.barangay }},<br>
                        {{ application.lot.property.city }},
                        {{ application.lot.property.province }},
                       <span v-if="application.type === 'Lot Application'">Lot {{ application.lot.id }} </span>



                    </td>
                    <td class="px-6 py-4">
                        <Tag v-if="application.status === 'For Review'" severity="info" :value="application.status"></Tag>
                        <Tag v-if="application.status === 'Approved'" severity="success" :value="application.status"></Tag>
                        <Tag v-if="application.status === 'Rejected'" severity="danger" :value="application.status"></Tag>
                    </td>
                    <td class="px-6 py-4" v-if="user.role_id != 3">
                        <v-icon v-if="application.status === 'For Review'" @click="handleStatusChange('Approved', application.id)" name="fc-approval" animation="ring" scale="2" class="text-4xl rounded cursor-pointer block float-left mr-2"/>
                        <v-icon v-if="application.status === 'For Review'" @click="handleStatusChange('Rejected', application.id)" fill="red" name="fa-window-close" animation="wrench" scale="2" class="text-4xl rounded cursor-pointer block float-left mr-2"/>

                    </td>
                </tr>
            </tbody>
        </v-table>
    </div> -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card card-statistics border-0 shadow-none mb-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-border-3">
                            <thead>
                                <tr>
                                    <th v-if="user.role_id != 3" scope="col" class="px-6 py-3">
                                        Applicant
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Application Type
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Reserved Date
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Lot Address
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th v-if="user.role_id != 3" scope="col" class="px-6 py-3">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="mb-0">
                                <tr v-for="application in applications" :key="application.id">
                                    <td v-if="user.role_id != 3" scope="row" >
                                        <a class="text-blue" style="color:blue;" href="#" @click="setClient(application.user)">{{ application.user.personal_info.first_name }} {{ application.user.personal_info.last_name }} </a>
                                    </td>
                                    <td>
                                        {{ application.application_type }}
                                    </td>
                                    <td>
                                        {{ application.reserved_date }}
                                    </td>
                                    <td>
                                        Phase {{ application.lot.property.phase.toLowerCase() }},
                                        purok {{ application.lot.property.purok.toLowerCase() }},
                                        barangay {{ application.lot.property.barangay.toLowerCase() }},<br>
                                        {{ application.lot.property.city.toLowerCase() }},
                                        {{ application.lot.property.province.toLowerCase() }},
                                        <span v-if="application.type === 'Lot Application'">lot {{ application.lot.id }} </span>
                                    </td>
                                    <td >
                                        <span class="badge badge-info" v-if="application.status === 'For Review'">
                                            {{ application.status }}
                                        </span>
                                        <span class="badge badge-danger" v-if="application.status === 'Rejected'">
                                            {{ application.status }}
                                        </span>
                                        <span class="badge badge-success" v-if="application.status === 'Approved'" severity="success">
                                            {{ application.status }}
                                        </span>

                                    </td>


                                    <td v-if="user.role_id != 3">
                                        <div class="dropdown">
                                            <a class="p-2" href="#!" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
                                                <h6 class="mb-1">Action</h6>
                                                <a  @click="handleStatusChange('Approved', application.id)" class="dropdown-item" href="#"><i class="fa-fw far fa-check-circle pr-2"></i>Approve</a>
                                                <a  @click="handleStatusChange('Rejected', application.id)" class="dropdown-item" href="#!"><i class="fa-fw fas fa-minus-circle pr-2"></i>Reject</a>
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
