<script setup>
import Layout from '../layout/main.vue'
import Widget from './widget.vue'
import QuarterlySales from './components/quarterly-sales.vue';

const props = defineProps(
    {
        clients: Object,
        applications: Object,
        payments: Number,
        properties: Number,
        sales: Object
    },


)
defineOptions({ layout: Layout })
</script>


<template>
    <Widget :clients="clients" :applications="applications" :payments="payments" :properties="properties" />
    <div class="grid grid-cols-2 gap-2 ">
        <div class="card bg-white items-center">
            <div class="p-3 text-2xl semi-bold tabular-nums">
                <h3>Clients</h3>
            </div>
            <div class="">
                <DataTable :value="clients" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" >
                    <Column field="id" header="ID"></Column>
                    <Column header="Name">
                        <template #body="slotProps">
                            {{ slotProps.data.personal_info.first_name }} 
                            {{ slotProps.data.personal_info.last_name }}  
                        </template>
                    </Column>
                    <Column header="Phone Number">
                        <template #body="slotProps">
                            {{ slotProps.data.personal_info.phone_number }} 
                        </template>
                    </Column>
                    <Column header="Address">
                        <template #body="slotProps">
                            {{ slotProps.data.personal_info.address }} 
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
        <div class="card bg-white items-center">
            <div class="p-3 text-2xl semi-bold tabular-nums">
                <h3>Applications</h3>
            </div>
            <div class="">
                <DataTable :value="applications" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]">
                    <Column field="application_type" header="Application Type"></Column>
                    <Column header="Client">
                        <template #body="slotProps">
                            {{ slotProps.data.user.personal_info.first_name }} 
                            {{ slotProps.data.user.personal_info.last_name }} 
                        </template>
                    </Column>
                    <Column header="Lot">
                        <template #body="slotProps">
                            Phase {{ slotProps.data.lot.property.phase }},
                            Purok {{ slotProps.data.lot.property.purok }},
                            Barangay {{ slotProps.data.lot.property.barangay }},
                            {{ slotProps.data.lot.property.region }} 
                        </template>
                    </Column>
                    <Column field="status" header="Status"></Column>
                </DataTable>
            </div>
        </div>
        
    </div>
    <div class="grid grid-cols-1 mt-4">
        <div class="card bg-white items-center py-4 px-4">
            <QuarterlySales :sales="sales"/>
        </div>
        <!-- <div class="card bg-white items-center py-4 px-4">
            <YearlySales />
        </div> -->
    </div>
</template>


