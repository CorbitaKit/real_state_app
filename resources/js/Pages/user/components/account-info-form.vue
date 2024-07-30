<script setup>

const props = defineProps({
    account_info: Object
})
</script>

<template>
    <div class="grid grid-cols-2 gap-4 mx-9">
        <div class="mb-4">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">Email</label>
            <InputText type="text" v-model="account_info.email" class="block appearance-none w-full px-3 py-2 border "/>        
        </div>
        <div class="mb-4">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">Password</label>
            <Password toggleMask v-model="account_info.password" class="w-full block"  :inputStyle="{ width: '100%' }" :style="{ width: '100%' }"/>        
        </div>
    </div>
</template>
<style scoped>
    .p-calendar > input.p-inputtext.p-component {
        border-radius: 7px !important;
    }
</style>