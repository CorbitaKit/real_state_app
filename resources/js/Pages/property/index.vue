<script setup>
import Layout from '../layout/main.vue'
import { router } from '@inertiajs/vue3'
import LandMark from './components/landmark.vue'
import Swal from 'sweetalert2'
import axios from 'axios'

const props = defineProps({
    properties: Object
})


const submit = () => {
    
    router.get('/properties/create')
}

const home = () => {
    router.get('/dashboard')
}

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
            text: "Your file has been deleted.",
            icon: "success"
        });
    }
});
}
defineOptions({layout: Layout})
</script>


<template>
    <!-- <Header @submit="submit" :title="'Property Page'" :displayBtn="setButton()" :btnTxt="'Create Property'" />
    <div class="grid grid-cols-3 gap-4 mt-5">
       <Card v-for="property in properties" :key="property.id" :property="property" :displayBtn="true"/>
    </div> -->
    
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
                    </div>
                    <div>
                        <a @click.prevent="show(property.id)" data-toggle="tooltip" data-placement="top" title="" data-original-title="View Property Details" href="javascript:void(0);" class="btn btn-xs btn-icon btn-round btn-outline-info"><i class="fas fa-eye"></i></a>
                        <a @click.prevent="deleteProperty(property.id)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Property" href="javascript:void(0);" class="btn btn-xs btn-icon btn-round btn-outline-danger ml-1"><i class="fas fa-trash"></i></a>
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