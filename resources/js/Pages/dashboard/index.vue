<script setup>
import Layout from '../layout/main.vue'
import QuarterlySales from './components/quarterly-sales.vue';
import Widget from './components/widget.vue'
import Sales from './components/sales.vue'
import Profile from './components/profile.vue'
import Property from './components/property.vue'
import Staffs from './components/staff.vue'
import Application from './components/application.vue'
import Payment from './components/payment.vue'
import Lot from './components/lot.vue'
import moment from 'moment'
const props = defineProps(
    {
        clients: Object,
        applications: Object,
        payments: Number,
        properties: Number,
        sales: Object,
        lot_application: Number,
        site_visit: Number,
        approved_payment: Number,
        pending_payment: Number,
        available_lot : Number,
        pending_lot : Number,
        occupied_lot : Number,
    },


)

const convertDate = (date) => {
    return moment(date).format("MMMM D, YYYY");
}
defineOptions({ layout: Layout })
</script>


<template>
    <div class="row">
        <Widget :properties="properties.length" :clients="clients.length" :applications="applications.length" :sales="sales.overall"/>
        <Sales />
        <Profile />
        <Application :lot_application="lot_application" :site_visit="site_visit"/>
        <Payment :pending_payment="pending_payment" :approved_payment="approved_payment"/>
        <Lot :available_lot="available_lot" :occupied_lot="occupied_lot" :pending_lot="pending_lot"/>
        <Property :properties="properties"/>
        
    </div>
    <div class="row">
        <div class="col-lg-5 col-xl-4 col-xxl-4 mb-3">
            <div class="card-heading">
                <h5 class="card-title">Applications Overview</h5>
            </div>
            <div class="card card-statistics border-0 shadow-none mb-0">
                
                <div class="card-body">
                    <ul class="activity">
                        <li class="activity-item primary" v-for="application in applications">
                            <div class="activity-icon text-warning">
                                <i v-if="application.application_type === 'Lot Application'" class="fe fe-map"></i>
                                <i v-else class="fe fe-calendar"></i>
                            </div>
                            <div class="activity-info">
                                <h6 class="mb-0">{{ application.application_type }}</h6>
                                <span>{{  convertDate(application.created_at) }}</span>
                            </div>
                        </li>
                       
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-xl-8 col-xxl-8 mb-3">
            <div class="card-heading">
                <h5 class="card-title">Payments Overview</h5>
            </div>
            <div class="scrollbar scroll_dark" style="height:436px;">
                <div class="card card-statistics border-0 shadow-none mb-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-border-3">
                                <thead>
                                    <tr>
                                        <th>Payment Proof</th>
                                        <th>Payment ID</th>
                                        <th>Mode of Payment</th>
                                        <th>Payment Date</th>
                                        <th>Client</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="mb-0">
                                    <tr v-for="payment in payments">
                                        <td>
                                            <div class="avatar avatar-lg">
                                                        <Image alt="Image" preview>
                                                <template #previewicon>
                                                    <i class="pi pi-search"></i>
                                                </template>
                                                <template #image>
                                                    <img :src="'/storage/'+payment.files[0]?.url" alt="image" class="tw-h-[50px]"/>
                                                </template>
                                                <template #preview="slotProps">
                                                    <img :src="'/storage/'+payment.files[0]?.url" alt="preview" :style="slotProps.style" @click="slotProps.onClick" />
                                                </template>
                                            </Image>
                                            </div>
                                        </td>
                                        <td> {{ payment.id }} </td>
                                        <td> {{ payment.date_of_payment }} </td>
                                        <td>
                                            {{ payment.mode_of_payment }}
                                        </td>
                                        <td>
                                            {{ payment.user.personal_info.first_name }} 
                                            {{ payment.user.personal_info.last_name }}

                                        </td>
                                        <td>
                                            {{ payment.amount }}
                                        </td>
                                        <td>
                                            {{ payment.status }}
                                        </td>
                                       
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</template>


