<script setup>
import { ref } from "vue";
import { usePropertyStore } from "../store/store";


const useProperty = usePropertyStore();
const color_pallete = ref ([
    {
        color: 'bg-red-600'
    },
    {
        color: 'bg-orange-600'
    },
    {
        color: 'bg-amber-600'
    },
    {
        color: 'bg-yellow-300'
    },
    {
        color: 'bg-green-600'
    },
    {
        color: 'bg-lime-600'
    },
    {
        color: 'bg-emerald-600'
    },
    {
        color: 'bg-teal-600'
    },
    {
        color: 'bg-cyan-600'
    },
    {
        color: 'bg-sky-600'
    },
    {
        color: 'bg-blue-600'
    },
    {
        color: 'bg-indigo-600'
    },
    {
        color: 'bg-violet-600'
    },
    {
        color: 'bg-purple-600'
    },
    {
        color: 'bg-fuchsia-600'
    },
    {
        color: 'bg-pink-600'
    },
    {
        color: 'bg-fuchsia-600'
    },
])
const setLots = () => {
    useProperty.property.lots = []
    for (var i = 0; i < useProperty.property.number_of_lot; i++) {
        useProperty.property.lots.push({
            id: i + 1,
            sqr_meter: '',
            color_label: '',
            is_set: false,
            status: 'Available'
        })
    }

}

const setLotGroups = () => {
    useProperty.property.lot_groups = []
    for (var i = 0; i < useProperty.property.number_of_lot_groups; i++) {
        useProperty.property.lot_groups.push({
            id: i + 1,
            color_label: '',
            sqr_meter: '',
            amount_per_sqr_meter: 0,
            monthly_amortizations: 0,
            total_amount: 0,
            is_set: false
        })
    }
}


const calculateTotalAmount = (lot_group) => {
    lot_group.total_amount = lot_group.amount_per_sqr_meter * lot_group.sqr_meter
    lot_group.is_set = true
}
</script>

<template>
    <div class="grid grid-cols-2 gap-3 mx-9 mb-5">
        <div class="mb-2">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Down Payment</label>
            <input v-model="useProperty.property.down_payment" type="number"  class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
            
        </div>
        <div class="mb-2">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Balance Payable in</label>
            <input v-model="useProperty.property.balance_payable" type="text"  class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
            
        </div>

        <div class="mb-2">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Number of Lot(s)</label>
            <input v-model="useProperty.property.number_of_lot" @blur="setLots" type="number" min="1" max="100" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        
        <div class="mb-2">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Number of Lot group(s)</label>
            <input v-model="useProperty.property.number_of_lot_groups" @blur="setLotGroups" type="number" min="1" max="5" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
            
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
            <input v-model="lot_group.sqr_meter" type="text" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2" style="white-space: nowrap;">Amount per sqr meter</label>
            <input v-model="lot_group.amount_per_sqr_meter" @blur="calculateTotalAmount(lot_group)" type="text" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2" style="white-space: nowrap;">Monthly Amortization</label>
            <input v-model="lot_group.monthly_amortizations" type="text" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Total Amount</label>
            <input disabled v-model="lot_group.total_amount" type="text" class="block appearance-none w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" />
        </div>
      
        <hr class="w-[950px]"/>
    </div>
    
   
   
</template>
