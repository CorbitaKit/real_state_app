<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import Wizard from './components/wizard.vue'
import { usePropertyStore } from './store/store'
import { router, useForm } from '@inertiajs/vue3'
import { useToaster } from '../composables/toast'


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
            show('success', 'Success', 'Property created successfully!')
            useProperty.resetProperty()
            router.get('/properties')
        })
    });
}
</script>


<template>
    <Header  :displayBtn="false" :title="'Property Creation Page'" />
    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
        <Wizard @submit="submit"/>
    </div>
</template>