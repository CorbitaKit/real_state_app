<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { router } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const props = defineProps({
    staffs: Object
})
defineOptions({layout: Layout})

const submit = () => {
    router.get('/users/create')
}

const deleteStaff = (id) => {
    Swal.fire({
        title: "Are you sure you want to remove this staff?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
    if (result.isConfirmed) {
        router.delete('/users/' + id)
        Swal.fire({
            title: "Deleted!",
            text: "Staff has been removed.",
            icon: "success"
        });
    }
});
}
</script>

<template>
    <div class="row">
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    <h1>Staffs</h1>
                </div>
                
                <div class="ml-auto d-flex align-items-center">
                    
                    <nav>
                        <ol class="breadcrumb p-0 m-b-0">
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/dashboard')"><i class="ti ti-home"></i></a>
                            </li>
                            
                            <li class="breadcrumb-item active text-primary" aria-current="page">Staffs</li>
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
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <tr v-for="client in staffs">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-lg mr-2">
                                            <img src="assets/img/avatar/01.jpg" class="img-fluid avatar-img rounded-circle" alt="Clients-01">
                                        </div>
                                        <p class="font-weight-bold text-dark">{{ client.personal_info.first_name }} {{ client.personal_info.last_name }}</p>
                                    </div>
                                </td>
                                <td> {{ client.email }}</td>
                                <td>{{ client.personal_info.phone_number }}</td>
                               
                                <td>
                                    <div class="dropdown">
                                            <a class="p-2" href="#!" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fe fe-more-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu custom-dropdown dropdown-menu-right p-4">
                                                <h6 class="mb-1">Action</h6>
                                                <a @click.prevent="deleteStaff(client.id)" class="dropdown-item" href="#!"><i class="fa-fw fas fa-trash pr-2"></i>Delete</a>
                                                
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