<script setup>

const props = defineProps({
    personal_info: Object,
    error: Boolean
})
</script>

<template>
    <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mx-9">
        <div class="tw-mb-4">
            <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">First Name</label>
            <InputText type="text" v-model="personal_info.first_name" class="tw-block text-black tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border"/>        
            <span v-if="error && !personal_info.first_name" style="color: red;">First name field is required</span>
            
        </div>
        <div class="tw-mb-4">
            <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Last Name</label>
            <InputText type="text" v-model="personal_info.last_name" class="tw-block text-black tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border"/>        
            <span v-if="error && !personal_info.last_name" style="color: red;">Last name field is required</span>
            
        </div>
        <div class="tw-mb-4">
            <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Phone Number</label>
            <InputMask id="basic" v-model="personal_info.phone_number" mask="(+639)999999999" placeholder="(+639)999999999" class="tw-block tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border "/>
            <span v-if="error && !personal_info.phone_number" style="color: red;">Phone number field is required</span>
            
        </div>
        <div class="tw-mb-4">
            <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Birth Day</label>
            <Calendar v-model="personal_info.birth_day" class="tw-w-full tw-rounded-md" style="border-radius: 7px;"/>
            <span v-if="error && !personal_info.birth_day" style="color: red;">Birthday field is required</span>

        </div>
    </div>
</template>
<style scoped>
    .p-calendar > input.p-inputtext.p-component {
        border-radius: 7px !important;
    }
</style>