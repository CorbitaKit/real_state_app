<script setup>
import { ref } from "vue";
import { usePropertyStore } from "../store/store";


const useProperty = usePropertyStore();
const color_pallete = ref ([
    {
        color: 'tw-bg-red-600'
    },
    {
        color: 'tw-bg-orange-600'
    },
    {
        color: 'tw-bg-amber-600'
    },
    {
        color: 'tw-bg-yellow-300'
    },
    {
        color: 'tw-bg-green-600'
    },
    {
        color: 'tw-bg-lime-600'
    },
    {
        color: 'tw-bg-emerald-600'
    },
    {
        color: 'tw-bg-teal-600'
    },
    {
        color: 'tw-bg-cyan-600'
    },
    {
        color: 'tw-bg-sky-600'
    },
    {
        color: 'tw-bg-blue-600'
    },
    {
        color: 'tw-bg-indigo-600'
    },
    {
        color: 'tw-bg-violet-600'
    },
    {
        color: 'tw-bg-purple-600'
    },
    {
        color: 'tw-bg-fuchsia-600'
    },
    {
        color: 'tw-bg-pink-600'
    },
    {
        color: 'tw-bg-fuchsia-600'
    },
])
const setLots = () => {
    useProperty.property.lots = []
    for (var i = 0; i < useProperty.property.number_of_lot; i++) {
        useProperty.property.lots.push({
            name: 'Lot ' +  (i + 1),
            sqr_meter: '',
            color_label: '',
            is_set: false,
            status: 'Available'
        })
    }

}

const setLotGroups = () => {
    const color_labels = ['tw-bg-green-200', 'tw-bg-rose-300', 'tw-bg-amber-200']
    useProperty.property.lot_groups = []
    for (var i = 0; i < useProperty.property.number_of_lot_groups; i++) {
        useProperty.property.lot_groups.push({
            id: i + 1,
            color_label: color_labels[i],
            sqr_meter: '',
            amount_per_sqr_meter: 0,
            monthly_amortizations: 0,
            total_amount: 0,
            is_set: false
        })
    }
}


const calculateTotalAmount = (lot_group) => {
    lot_group.total_amount = (lot_group.amount_per_sqr_meter * lot_group.sqr_meter)
    lot_group.is_set = true
    calculateMonthly(lot_group)
}

const calculateMonthly = (lot_group) => {
    // const monthly = (useProperty.property.down_payment / useProperty.property.balance_payable)
    // useProperty.property.lot_groups.forEach(lot => {
    //     lot.monthly_amortizations = monthly
    // })

    lot_group.monthly_amortizations = lot_group.total_amount / useProperty.property.balance_payable
    useProperty.property.down_payment = lot_group.monthly_amortizations

}
</script>

<template>
    <!-- <div class="grid grid-cols-2 gap-3 mx-9 mb-5">
        <div class="mb-2">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Down Payment</label>
            <input v-model="useProperty.property.down_payment" type="number"  class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
            
        </div>
        <div class="mb-2">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Balance Payable in</label>
            <InputNumber v-model="useProperty.property.balance_payable" inputId="expiry" suffix=" Month(s)" fluid class="w-full" />
        </div>

        <div class="mb-2">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Number of Lot(s)</label>
            <InputNumber :min="0" :max="100" @blur="setLots" v-model="useProperty.property.number_of_lot" inputId="integeronly" fluid class="w-full"/>
        </div>
        
        <div class="mb-2">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Number of Lot group(s)</label>
            <InputNumber :min="0" :max="5"  @blur="setLotGroups" v-model="useProperty.property.number_of_lot_groups" inputId="integeronly" fluid class="w-full"/>
        </div>
    </div>
    <hr />
    <div class="grid grid-cols-5 gap-3 mx-9 mt-2 mb-5" v-for="lot_group in useProperty.property.lot_groups" :key="lot_group.id">
        <div class="mb-2">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Color label</label>
            <button v-if="lot_group.color_label" @click="lot_group.color_label = ''" type="button" class="block w-full h-[42px] items-center rounded p-4 text-sm font-medium transition hover:scale-105" :class="lot_group.color_label.color" />
            <Dropdown v-else v-model="lot_group.color_label"  :options="color_pallete" filter optionLabel="color" placeholder="Select a color for this lot group" class="w-full md:w-full border">
                <template #option="slotProps">
                    <div class="flex items-center">
                        <button type="button" class="block w-[100%] items-center rounded p-4 text-sm font-medium transition hover:scale-105" :class="slotProps.option.color" />
                    </div>
                </template>
            </Dropdown>
            
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">sqr meter</label>
            <input v-model="lot_group.sqr_meter" type="number" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2" style="white-space: nowrap;">Amount per sqr meter</label>
            <input v-model="lot_group.amount_per_sqr_meter" @blur="calculateTotalAmount(lot_group)" type="number" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Total Amount</label>
            <input disabled v-model="lot_group.total_amount" type="text" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2" style="white-space: nowrap;">Monthly Amortization</label>
            <input disabled v-model="lot_group.monthly_amortizations" type="number" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
        </div>
       
      
        <hr class="w-[950px]"/>
    </div>
     -->
   
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-statistics">
                <div class="card-header">
                    <div class="card-heading">
                        <h5 class="card-title">Lot Details</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Number of Lots</label>
                                <InputNumber :min="0" :max="100" @blur="setLots" v-model="useProperty.property.number_of_lot" inputId="integeronly" class="w-100" />
                               
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Number of Lot Group(s)</label>
                                <InputNumber :min="3" :max="3"  @blur="setLotGroups" v-model="useProperty.property.number_of_lot_groups" inputId="integeronly" class="w-100" />
                            </div>
                        </div>
                        <div class="form-row">
                            <!-- <div class="form-group col-md-6">
                                <label for="downpayment">Down Payment</label>
                                <InputNumber disabled v-model="useProperty.property.down_payment" inputId="currency-ph" mode="currency" currency="PHP" locale="en-PH" class="w-100" />
                                
                            </div> -->
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Balance Payable</label>
                                <InputNumber disabled v-model="useProperty.property.balance_payable" class="w-100" name="downpayment" inputId="expiry" suffix=" Month(s)" fluid />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="tw-grid tw-grid-cols-5 tw-gap-3 tw-mx-9 tw-mt-2 tw-mb-5" v-for="lot_group in useProperty.property.lot_groups" :key="lot_group.id">
        <div class="tw-mb-2">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Color label</label>
            <button type="button" class="tw-block tw-w-full tw-h-[38px] tw-items-center tw-rounded tw-p-4 tw-text-sm tw-font-medium tw-transition tw-hover:scale-105" :class="lot_group.color_label" />
            <!-- <Dropdown v-else v-model="lot_group.color_label"  :options="color_pallete" filter optionLabel="color" placeholder="Select a color for this lot group" class="tw-w-full md:tw-w-full border">
                <template #option="slotProps">
                    <div class="flex items-center">
                        <button type="button" class="block w-[100%] items-center rounded p-4 text-sm font-medium transition hover:scale-105" :class="slotProps.option.color" />
                    </div>
                </template>
            </Dropdown> -->
            
        </div>
        <div class="tw-mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">sqr meter</label>
            <InputNumber v-model="lot_group.sqr_meter" inputId="integeronly" class="w-100"/>
        </div>
        <div class="tw-mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2" style="white-space: nowrap;">Amount per sqr meter</label>
            <!-- <input v-model="lot_group.amount_per_sqr_meter" @blur="calculateTotalAmount(lot_group)" type="number" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" /> -->
            <InputNumber v-model="lot_group.amount_per_sqr_meter" inputId="integeronly" class="w-100" @blur="calculateTotalAmount(lot_group)"/>
        </div>
        <div class="tw-mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Total Amount</label>
            <!-- <input disabled v-model="lot_group.total_amount" type="text" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" /> -->
            <InputNumber disabled v-model="lot_group.total_amount" inputId="integeronly" class="w-100"/>
            
        </div>
        <div class="mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2" style="white-space: nowrap;">Monthly Amortization/Downpayment</label>
            <!-- <input disabled v-model="lot_group.monthly_amortizations" type="number" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" /> -->
            <InputNumber disabled v-model="lot_group.monthly_amortizations" inputId="integeronly" class="w-100"/>
            
        </div>
       
      
        <hr class="tw-w-[500%]"/>
    </div>
</template>
