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
</script>

<template>
    <div class="grid grid-cols-2 gap-4 mx-9">
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Company Name</label>
            <InputText type="text" v-model="work_details.company_name" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Company Address</label>
            <InputText type="text" v-model="work_details.company_address" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
                
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Company Email</label>
            <InputText type="text" v-model="work_details.company_email" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
                
        </div>
        <div class="mb-4">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">Company Number</label>
            <InputMask id="basic" v-model="work_details.company_number" mask="(+639)999999999" placeholder="(+639)999999999" class="block appearance-none w-full px-3 py-2 border "/>
        </div>

        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Job Title</label>
            <InputText type="text" v-model="work_details.job_title" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
                
        </div>

        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Position</label>
            <InputText type="text" v-model="work_details.position" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
                
        </div>
        
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2 rounded-md">Length of stay</label>
            <InputNumber v-model="work_details.length_of_stay" class="w-full" suffix=" year(s)"/>        
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Employment Status</label>
            <Dropdown v-model="work_details.status" :options="statuses" filter optionLabel="status" placeholder="Select a status" class="w-full md:w-full border" />    
        </div>

        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2 rounded-md">Gorss monthly income</label>
            <InputNumber v-model="work_details.gross_monthly_income" inputId="currency-ph" mode="currency" currency="PHP" locale="en-PH" fluid  class="w-full"/>
        </div>


        
    </div>
</template>
<style>
    .p-inputnumber-input{
        border-radius: 7px !important;
    }
</style>