<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import { ref } from 'vue'


defineOptions({layout: Layout})

const visible = ref(false)
const payments = ref()

const props = defineProps({
    client_lots: Object
})

const calculateTotalAmount = (lot_group) => {
    return lot_group.sqr_meter * lot_group.amount_per_sqr_meter
}

const calculateRemainingBalance = (lot) => {
    const total_amount = calculateTotalAmount(lot.lot_group)
    const total_payment = lot.payments.reduce((sum, payment) => sum + payment.amount, 0)

    return total_amount - total_payment
}

const viewPaymentHistory = (lot_payments) => {
    visible.value = true
    payments.value = lot_payments
}
</script>

<template>
    <Header  :displayBtn="false" :title="'My Properties'" />
    <Dialog v-model:visible="visible" modal header="Payment History" :style="{ width: '50rem' }">
        <v-table>
    <thead>
      <tr>
        <th class="text-left">
          Amount
        </th>
        <th class="text-left">
          Mode Of Payment
        </th>
        <th class="text-left">
          Payment Date
        </th>
        <th class="text-left">
          Invoice Number
        </th>

      </tr>
    </thead>
    <tbody>
      <tr v-for="payment in payments" :key="payment.id" >
        <td>{{ payment.amount }}</td>
        <td>{{ payment.mode_of_payment }}</td>
        <td>{{ payment.date_of_payment }}</td>
        <td>{{ payment.invoice_number }}</td>
      </tr>
    </tbody>
  </v-table>
    </Dialog>
    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
        <div class="relative overflow-x-auto mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Property
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Square Meter
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Amount Per Square Meter
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total Amount
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Remaining Balance
                    </th>
                
                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
               
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="lot in client_lots" :key="lot.id">
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
                        {{ lot.lot_group.amount_per_sqr_meter }}
                    </td>
                    
                    <td class="px-6 py-4">
                        {{ calculateTotalAmount(lot.lot_group) }}
                    </td>
                    <td class="px-6 py-4">
                        {{ calculateRemainingBalance(lot) }}
                    </td>
                    <td class="px-6 py-4">
                        <Button @click="viewPaymentHistory(lot.payments)" label="View Payments" severity="success" />
                    </td>
                    

                   
                </tr>
            </tbody>
        </table>
    </div>
    </div>
</template>