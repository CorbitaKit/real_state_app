<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    staffs: Object
})
defineOptions({layout: Layout})

const submit = () => {
    router.get('/users/create')
}
</script>

<template>
    <Header @submit="submit" :title="'Staff Page'"   :btnTxt="'Create Staff Account'" />
    <div class="mx-auto bg-white p-4 my-4 rounded shadow-md">
        <DataTable :value="staffs" showGridlines tableStyle="min-width: 50rem">
            <Column header="Name">
                <template #body="slotProps">
                    {{ slotProps.data.personal_info.first_name }} 
                    {{ slotProps.data.personal_info.last_name }} 
                </template>
            </Column>
            <Column field="email" header="Email"></Column>
            <Column header="Phone Number">
                <template #body="slotProps">
                    {{ slotProps.data.personal_info.phone_number }} 
                </template>
            </Column>
            <Column header="Phone Number">
                <template #body="slotProps">
                    {{ slotProps.data.personal_info.phone_number }} 
                </template>
            </Column>
        </DataTable>
    </div>
</template>