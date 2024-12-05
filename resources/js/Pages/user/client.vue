<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import FileUpload from '../components/fileupload.vue'
import { router, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'


const props = defineProps({
    clients: Object
})
const visible = ref(false)
const payment = ref(false)
const form = useForm({
    file: {}
})
defineOptions({layout: Layout})

const submit = () => {
    router.get('/users/create-client')
}

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
      }).format(value);
}
</script>

<template>
    
    <Dialog v-model:visible="payment" modal header="Make Payment" :style="{ width: '50rem' }">
        <span class="tw-text-surface-500 tw-dark:text-surface-400 tw-block tw-mb-8">Please input invoice # number and attach the actual invoice</span>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Amount</label>
            <InputText class="tw-w-full tw-rounded-md"/>
           
        </div>
        <div class="tw-flex tw-items-center tw-gap-4 tw-mb-4">
            <label for="username" class="tw-font-semibold tw-w-24">Invoice Number</label>
            <InputText class="tw-w-full tw-rounded-md"/>
           
        </div>
        <FileUpload :file="form.file" />
        <div class="tw-flex tw-justify-end tw-gap-2">
            <Button type="button" label="Save" @click="submitPayment"></Button>
        </div>
    </Dialog> 
  
    <div class="row">
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
                                    <div class="d-flex align-items-center">
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
                                                <i class="fe fe-more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
                                                <h6 class="mb-1">Action</h6>
                                                <a class="dropdown-item" href="#!"><i class="fa-fw far fa-file-alt pr-2"></i>View Payment History</a>
                                                <a class="dropdown-item" href="#!"><i class="fa-fw fas fa-eye pr-2"></i>View Profile</a>
                                                <a class="dropdown-item" href="#!"><i class="fa-fw fas fa-trash pr-2"></i>Delete</a>
                                                <a @click.prevent="payment = true" class="dropdown-item" href="#!"><i class="fa-fw fas fa-receipt pr-2"></i>Make Payment</a>
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