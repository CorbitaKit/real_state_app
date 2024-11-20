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
    <div class="row align-items-center mb-2 mb-sm-3">
        <div class="col-xxl-5 col-xl-5 mr-auto">
            <h3 class="mb-1">Welcome (User)!</h3>
        </div>
        <div class="col-xxl-6 col-xl-7 mt-4 mt-xl-0">
            <div class="row align-items-center secondary-menu text-center justify-content-xxl-end">
                <div class="col-6 col-sm-3 border-right text-left mb-2 mb-md-0">
                    <h6 class="mb-0 font-md"><i class="fas fa-users text-success mr-1"></i> Clients</h6>
                    <b class="d-block">3,3265</b>
                </div>
                <div class="col-6 col-sm-3 border-0 border-sm-right text-left mb-2 mb-md-0">
                    <h6 class="mb-0 font-md"><i class="fas fa-file-alt text-danger mr-1"></i> Applications</h6>
                    <b class="d-block">6,6484</b>
                </div>
                <div class="col-6 col-sm-3 border-right text-left mb-2 mb-md-0">
                    <h6 class="mb-0 font-md"><i class="far fa-calendar-alt text-cyan mr-1"></i> Calendar</h6>
                    <b class="d-block">2,2646</b>
                </div>
                <div class="col-6 col-sm-3 text-left mb-2 mb-md-0">
                    <h6 class="mb-0 font-md"><i class="far fa-chart-bar text-pink mr-1"></i> Analytics</h6>
                    <b class="d-block">4,6587</b>
                </div>
            </div>
        </div>
    </div>
    <!-- <Widget :clients="clients" :applications="applications" :payments="payments" :properties="properties" />
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
      
    </div> -->
</template>


