<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import Wizard from './components/wizard.vue'
import { usePropertyStore } from './store/store'
import { router, useForm } from '@inertiajs/vue3'
import { useToaster } from '../composables/toast'
import Swal from 'sweetalert2'

const { show } = useToaster()
const useProperty = usePropertyStore()

defineOptions({layout: Layout})

const submit = () => {
    const form = useForm(useProperty.property)
    form.transform((data) => {
        const { number_of_lot, number_of_lot_groups, ...rest } = data;
        return {
            ...rest,
            province: data.province.province_name,
            city: data.city.city_name,
            barangay: data.barangay.brgy_name,
            region: data.region.region_name
        };
    }).post('/properties', {
        onSuccess: (() => {
            Swal.fire({
                title: "Success!",
                text: "Property Created Successfully!",
                icon: "success"
            });
            useProperty.resetProperty()
            router.get('/properties')
        })
    });
}

const redirect = (route) => {
    router.get(route)
}
</script>


<template>
    <!-- <Header  :displayBtn="false" :title="'Property Creation Page'" />
    <Toast />
    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
        <Wizard @submit="submit"/>
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
                                <a href="#" @click.prevent="redirect('/dashboard')"><i class="ti ti-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#" @click.prevent="redirect('/properties')">Properties</a>
                            </li>
                        
                            <li class="breadcrumb-item active text-primary" aria-current="page">Create Property</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
                <Wizard @submit="submit"/>
            </div>
        </div>
    </div>
</template>