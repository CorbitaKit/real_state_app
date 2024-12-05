<script setup>
import { ref } from "vue";
import { usePropertyStore } from "../store/store";

const useProperty = usePropertyStore();
const block = ref()

const setBlock = (block_data) => {
    block.value = block_data
}

const setLotValue = (lot) => {
    lot.block = block.value
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
        Click the block first and then click the lot you want to set the lot block
    </div>
    <span class="tw-mb-5 tw-text-black tw-font-weight-bold tw-text-xl">Legend:</span>
    <div class="tw-grid tw-grid-cols-4 tw-gap-3 tw-mb-4 ">
        <div class="tw-mb-4 tw-p-4"  v-for="(block, i) in useProperty.property.blocks" :key="i.id">
            
            <button @click="setBlock(block)" type="button" class="tw-block tw-w-full tw-items-center tw-rounded tw-p-4 tw-text-sm tw-font-medium tw-transition tw-hover:scale-105 tw-bg-gray-200">
                <span class="tw-text-black tw-font-weight-bold tw-text-xl">Block - {{block }} </span><br>
            </button>
        </div>
    </div>
    <div class="tw-grid tw-grid-cols-4 tw-gap-3">
        
        <div class="tw-mb-4" v-for="lot in useProperty.property.lots" :key="lot.name">
            
            <button @click="setLotValue(lot)" type="button" class="tw-block tw-w-full rounded tw-p-4 text-sm tw-font-medium tw-transition tw-hover:scale-105" :class="[lot.color_label ? lot.color_label : 'tw-bg-gray-200']">
               <span class="tw-text-black tw-font-weight-bold tw-text-base"> {{ lot.name }}</span><br>
               <span class="tw-text-black tw-font-weight-bold tw-text-base"> {{ lot.sqr_meter }} sqr meter</span><br>
               <span v-if="lot.block" class="tw-text-black tw-font-weight-bold tw-text-base"> Block - {{ lot.block }}</span><br>
   
            </button>
        </div>
        
    </div>
</template>