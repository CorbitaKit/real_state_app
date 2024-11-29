<script setup>

const props = defineProps({
    account_info: Object,
    error: Boolean
})
</script>

<template>
    <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mx-9">
        <div class="tw-mb-4">
            <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Email *</label>
            <InputText type="text" v-model="account_info.email" class="tw-block tw-appearance-none tw-w-full tw-px-3 tw-py-2 tw-border "/>        
            <span v-if="error && !account_info.email" style="color: red;">Email field is required</span>
        </div>
        <div class="tw-mb-4">
            <label for="phase" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Password</label>
            <Password  v-model="account_info.password" class="tw-w-full block"  />        
            <span v-if="error && !account_info.password" style="color: red;">Password field is required</span>

        </div>
    </div>
</template>
