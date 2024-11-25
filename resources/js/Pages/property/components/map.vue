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
    <!-- <Message  severity="info">Important Note: This is not an actual map of the property, this is only a visual represantion of the lots and it's details</Message>
    <Message  severity="success">Click the legend first and then click the lot you want to set into lot group</Message> -->
    <div class="alert alert-dark tw-mb-2 tw-text-black tw-font-weight-bold tw-text-xl" role="alert">
        Important Note: This is not an actual map of the property, this is only a visual represantion of the lots and it's details
    </div>

    <div class="alert alert-info tw-mb-4 tw-text-black tw-font-weight-bold tw-text-xl" role="alert">
        Click the legend first and then click the lot you want to set into lot group
    </div>
    <span class="tw-mb-5 tw-text-black tw-font-weight-bold tw-text-xl">Legend:</span>
    <div class="tw-grid tw-grid-cols-4 tw-gap-3 tw-mb-4 ">
        <div class="tw-mb-4 tw-p-4"  v-for="lot_group in useProperty.property.lot_groups" :key="lot_group.id">
            
            <button @click="setProperty(lot_group)" type="button" class="tw-block tw-w-full tw-items-center tw-rounded tw-p-4 tw-text-sm tw-font-medium tw-transition tw-hover:scale-105" :class="lot_group.color_label">
                <span class="tw-text-black tw-font-weight-bold tw-text-xl">{{ lot_group.amount_per_sqr_meter }} - Per sqr meter</span><br>
                <span class="tw-text-black tw-font-weight-bold tw-text-xl">{{ lot_group.sqr_meter }} sqr meter</span>
            </button>
        </div>
    </div>
    <div class="tw-grid tw-grid-cols-4 tw-gap-3">
        
        <div class="tw-mb-4" v-for="lot in useProperty.property.lots" :key="lot.name">
            
            <button @click="setLotValue(lot)" type="button" class="tw-block tw-w-full rounded tw-p-4 text-sm tw-font-medium tw-transition tw-hover:scale-105" :class="[lot.color_label ? lot.color_label : 'tw-bg-gray-200']">
               <span class="tw-text-black tw-font-weight-bold tw-text-base"> {{ lot.name }}</span><br>
               <span class="tw-text-black tw-font-weight-bold tw-text-base"> {{ lot.sqr_meter }} sqr meter</span><br>
            </button>
        </div>
        
    </div>
</template>