<script setup>
import Layout from '../layout/main.vue'
import QuarterlySales from './components/quarterly-sales.vue';
import Widget from './components/widget.vue'
import Sales from './components/sales.vue'
import Profile from './components/profile.vue'
import Property from './components/property.vue'
import Staffs from './components/staff.vue'
import Application from './components/application.vue'
import {getUser} from '../plugins/get-user-plugin'

import Payment from './components/payment.vue'
import Lot from './components/lot.vue'
import { formatCurrency } from '../composables/currencyFormatter'

import moment from 'moment'

const { getUserInfo } = getUser()

const user = getUserInfo()
const props = defineProps(
    {
        daily: Object,
        weekly: Object,
        monthly: Object,
        clients: Object,
        applications: Object,
        payments: Number,
        properties: Number,
        sales: Object,
        for_review_application: Number,
        rejected_application: Number,
        approved_application: Number,
        approved_payment: Number,
        pending_payment: Number,
        available_lot : Number,
        pending_lot : Number,
        occupied_lot : Number,
        lots: Object,
        payment_plans: Object,
    },


)

const convertDate = (date) => {
    return moment(date).format("MMMM D, YYYY");
}

const calculateTotalAmount = (lot_group) => {
    return lot_group.sqr_meter * lot_group.amount_per_sqr_meter
}

const calculateRemainingBalance = (lot) => {
    const total_amount = calculateTotalAmount(lot.lot_group)
    const total_payment = lot.payments.reduce((sum, payment) => sum + payment.amount, 0)

    return total_amount - total_payment
}


defineOptions({ layout: Layout })
</script>


<template>
    <div>
        <div class="row" >
            <div class="col-lg-5 col-xl-4 col-xxl-4 mb-3" v-if="user.role.name === 'Client'">
                <div class="card-heading">
                    <h5 class="card-title">Upcoming Due Dates</h5>
                </div>
                <div class="card card-statistics border-0 shadow-none mb-0">

                    <div class="card-body">
                        <ul class="activity">
                            <li class="activity-item primary" v-for="payment_plan in payment_plans">
                                <div class="activity-icon text-warning">

                                    <i class="fe fe-calendar"></i>
                                </div>
                                <div class="activity-info">

                                    <span>Block {{  payment_plan.lot.block }} Phase{{  payment_plan.lot.property.phase }} Barangay {{ payment_plan.lot.property.barangay }} {{ payment_plan.lot.property.city }} City</span>&nbsp;
                                    <span>Amount:  {{  formatCurrency(payment_plan.lot.lot_group.monthly_amortizations)}}</span><br>
                                    <span>Payment Date: {{  payment_plan.due_date}}</span>
                                </div>

                            </li>

                        </ul>
                    </div>
                </div>
            </div>
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
                                <div class="activity-info" v-if="user.role.name != 'Client'">
                                    <a href="/applications"><h6 class="mb-0">{{ application.application_type }}</h6></a>
                                    <span>{{  application .user.personal_info.first_name }} </span>&nbsp;
                                    <span> {{  application .user.personal_info.last_name}}</span>
                                </div>
                                <div class="activity-info" v-else>
                                    <a href="/application/get-by-user"><h6 class="mb-0">{{ application.application_type }}</h6></a>
                                    <span>{{  application.status }} </span>
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
                <div class="scrollbar scroll_dark" >
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
                                            <th v-if="user.role.name != 'Client'">Client</th>
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
                                                        <img :src="'/storage/app/public/'+payment.files[0]?.url" alt="image" class="tw-h-[50px]"/>
                                                    </template>
                                                    <template #preview="slotProps">
                                                        <img :src="'/storage/app/public/'+payment.files[0]?.url" alt="preview" :style="slotProps.style" @click="slotProps.onClick" />
                                                    </template>
                                                </Image>
                                                </div>
                                            </td>
                                            <td> {{ payment.id }} </td>
                                            <td>
                                                {{ payment.mode_of_payment }} </td>
                                            <td>
                                                {{ payment.date_of_payment }}
                                            </td>
                                            <td v-if="user.role.name != 'Client'">
                                                {{ payment.user?.personal_info.first_name }}
                                                {{ payment.user?.personal_info.last_name }}

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
        <div class="row" v-if="user.role.name === 'Client'">
            <div class="col-xl-12">
                <div class="card card-statistics border-0 shadow-none mb-0">
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table mb-0 table-border-3">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            Property
                                        </th>
                                        <th scope="col">
                                            Square Meter
                                        </th>
                                        <th scope="col">
                                            Amount Per Square Meter
                                        </th>
                                        <th scope="col">
                                            Total Amount
                                        </th>
                                        <th scope="col">
                                            Remaining Balance
                                        </th>

                                    </tr>
                                </thead>
                                <tbody class="mb-0">
                                    <tr  v-for="lot in lots" :key="lot.id">
                                        <td class="px-6 py-4">

                                            Phase {{ lot.property.phase }},
                                            purok {{ lot.property.purok }},
                                            barangay {{ lot.property.barangay }},
                                            {{ lot.property.city }},
                                            {{ lot.property.province }},
                                            {{ lot.name }}

                                        </td>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ lot.lot_group.sqr_meter }} Square Meters
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ formatCurrency(lot.lot_group.amount_per_sqr_meter) }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ formatCurrency(calculateTotalAmount(lot.lot_group)) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ formatCurrency(calculateRemainingBalance(lot)) }}
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row" v-if="user.role.name != 'Client'">
            <Widget :properties="properties.length" :clients="clients.length" :applications="applications.length" :sales="sales.overall" v-if="user.role.name "/>
            <Sales :daily="daily" :weekly="weekly" :monthly="monthly"/>
            <!-- <Profile :clients="clients"/> -->
            <Application :for_review_application="for_review_application" :rejected_application="rejected_application" :approved_application="approved_application"/>
            <Payment :pending_payment="pending_payment" :approved_payment="approved_payment"/>
            <Lot :available_lot="available_lot" :occupied_lot="occupied_lot" :pending_lot="pending_lot"/>
            <Property :properties="properties"/>

        </div>
    </div>


</template>


