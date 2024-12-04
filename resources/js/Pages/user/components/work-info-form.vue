<script setup>

const props = defineProps({
    work_details: Object
})

const statuses = [
    {
        status: 'Casual'
    },
    {
        status: 'Job Order'
    },
    {
        status: 'Contractual'
    },
    {
        status: 'Regular'
    }
]

const emits = defineEmits(['skip'])


</script>

<template>
    
    <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mx-9">
        
        <div class="tw-mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Company Name</label>
            <InputText type="text" v-model="work_details.company_name" class="tw-block tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border tw-rounded-md"/>        
        </div>
        <div class="mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Company Address</label>
            <InputText type="text" v-model="work_details.company_address" class="tw-block tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border tw-rounded-md"/>        
                
        </div>
        <div class="mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Company Email</label>
            <InputText type="text" v-model="work_details.company_email" class="tw-block tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border tw-rounded-md"/>        
                
        </div>
        <div class="mb-4">
            <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Company Number</label>
            <InputMask id="basic" v-model="work_details.company_number" mask="(+639)999999999" placeholder="(+639)999999999" class="tw-block tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border tw-rounded-md"/>
        </div>

        <div class="mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Job Title</label>
            <InputText type="text" v-model="work_details.job_title" class="tw-block tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border tw-rounded-md"/>        
                
        </div>

        <div class="mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Position</label>
            <InputText type="text" v-model="work_details.position" class="tw-block tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border tw-rounded-md"/>        
                
        </div>
        
        <div class="mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Length of Service</label>
            <InputNumber v-model="work_details.length_of_stay" class="tw-w-full" suffix=" year(s)"/>        
        </div>
        <div class="mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Employment Status</label>
            <!-- <Dropdown v-model="work_details.status" :options="statuses" filter optionLabel="status" placeholder="Select a status" class="tw-w-full md:w-full border" />     -->
            <select  class="js-basic-single form-control" name="status"  v-model="work_details.status" >
                <option  v-for="status in statuses" :value="status">{{ status.status }}</option>
            </select>
        </div>

        <div>
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Gross monthly income</label>
            <InputNumber v-model="work_details.gross_monthly_income" inputId="currency-ph" mode="currency" currency="PHP" locale="en-PH" fluid  class="tw-w-full"/>
        </div>
        <div class="tw-mt-8">
            <a href="javascript:void(0);" class="btn btn-primary pull-right" @click="$emit('skip')">Skip</a>
        </div>

        


        
    </div>
</template>
<style>
    .p-inputnumber-input{
        border-radius: 7px !important;
    }
</style>