<script setup>

const props = defineProps({
    personal_info: Object
})
</script>

<template>
    <div class="grid grid-cols-2 gap-4 mx-9">
        <div class="mb-4">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">First Name</label>
            <InputText type="text" v-model="personal_info.first_name" class="block appearance-none w-full px-3 py-2 border "/>        
        </div>
        <div class="mb-4">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">Last Name</label>
            <InputText type="text" v-model="personal_info.last_name" class="block appearance-none w-full px-3 py-2 border "/>        
        </div>
        <div class="mb-4">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">Phone Number</label>
            <InputMask id="basic" v-model="personal_info.phone_number" mask="(+639)999999999" placeholder="(+639)999999999" class="block appearance-none w-full px-3 py-2 border "/>
        </div>
        <div class="mb-4">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">Birth Day</label>
            <Calendar v-model="personal_info.birth_day" class="w-full rounded-md" style="border-radius: 7px;"/>
        </div>
    </div>
</template>
<style scoped>
    .p-calendar > input.p-inputtext.p-component {
        border-radius: 7px !important;
    }
</style>