<script setup>
import { ref } from "vue";
import { usePropertyStore } from "../store/store";

const useProperty = usePropertyStore();
const property = ref()

const setProperty = (lot_group) => {
    property.value = lot_group
}

const setLotValue = (lot) => {
    lot.color_label = property.value.color_label
    lot.sqr_meter = property.value.sqr_meter
    lot.is_set = true
}

</script>

<template>
    <Message  severity="info">Important Note: This is not an actual map of the property, this is only a visual represantion of the lots and it's details</Message>
    <Message  severity="success">Click the legend first and then click the lot you want to set into lot group</Message>
    
    <span class="mb-5">Legend:</span>
    <div class="grid grid-cols-4 gap-3 mb-4 ">
        <div class="mb-4 p-4"  v-for="lot_group in useProperty.property.lot_groups" :key="lot_group.id">
            
            <button @click="setProperty(lot_group)" type="button" class="block w-full items-center rounded p-4 text-sm font-medium transition hover:scale-105" :class="lot_group.color_label.color">
                <span>{{ lot_group.amount_per_sqr_meter }} - Per sqr meter</span><br>
                <span>{{ lot_group.sqr_meter }} sqr meter</span>
            </button>
        </div>
    </div>
    <div class="grid grid-cols-4 gap-3">
        
        <div class="mb-4" v-for="lot in useProperty.property.lots" :key="lot.name">
            
            <button @click="setLotValue(lot)" type="button" class="block w-full rounded p-4 text-sm font-medium transition hover:scale-105" :class="[lot.color_label ? lot.color_label.color : 'bg-gray-200']">
               <span> {{ lot.name }}</span><br>
               <span> {{ lot.sqr_meter }} sqr meter</span><br>
            </button>
        </div>
        
    </div>
</template>