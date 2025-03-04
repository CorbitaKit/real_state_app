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
        <div class="row" v-if="user.role.name === 'Admin'">
            <Widget :properties="properties.length" :clients="clients.length" :applications="applications.length" :sales="sales.overall" v-if="user.role.name "/>
        </div>
        <div class="row" >
            <div class="col-lg-5 col-xl-4 col-xxl-4 mb-3">
                <div class="card-heading">
                    <h5 class="card-title">Applications Overview</h5>
                </div>
                <div class="card card-statistics border-0 shadow-none mb-0">

                    <div class="card-body">
                        <div v-if="applications.length == 0">
                            No Applications Yet
                        </div>
                        <ul class="activity" v-else>
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
                    <h5 class="card-title">Upcoming Payments</h5>
                </div>
                <div class="scrollbar scroll_dark" >
                    <div class="card card-statistics border-0 shadow-none mb-0">
                        <div class="card-body">
                            <div v-if="payment_plans.length == 0" class="flex items-center gap-4">
                                No Upcoming Payment Yet
                            </div>
                            <div class="table-responsive" v-else>
                                <table class="table mb-0 table-border-3">
                                    <thead>
                                        <tr>
                                            <th>Due Date</th>
                                            <th>Amount</th>
                                            <th>Property</th>
                                            <th v-if="user.role.name === 'Admin'">Client</th>

                                        </tr>
                                    </thead>
                                    <tbody class="mb-0">
                                        <tr v-for="plan in payment_plans">
                                            <td>
                                                {{ plan.due_date }}
                                            </td>
                                            <td>
                                                {{ formatCurrency( plan.lot.lot_group.monthly_amortizations) }}
                                            </td>
                                            <td>
                                                <span>Block {{  plan.lot.block }} Phase{{  plan.lot.property.phase }} Barangay {{ plan.lot.property.barangay }} {{ plan.lot.property.city }} City</span>&nbsp;
                                            </td>
                                            <td v-if="user.role.name === 'Admin'">
                                                {{ plan.user.personal_info?.first_name }}
                                                {{ plan.user.personal_info?.last_name }}
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

            <Sales :daily="daily" :weekly="weekly" :monthly="monthly"/>
            <Application :clients="clients"/>
            <Payment :pending_payment="pending_payment" :approved_payment="approved_payment"/>
            <Lot :available_lot="available_lot" :occupied_lot="occupied_lot" :pending_lot="pending_lot"/>
            <Property :properties="properties"/>

        </div>
    </div>


</template>


