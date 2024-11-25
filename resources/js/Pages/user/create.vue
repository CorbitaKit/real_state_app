<script setup>
import Layout from '../layout/main.vue'
import PersonalInfoForm from './components/personal-info-form.vue'
import PersonalAddressForm from './components/personal-address-form.vue'
import WorkInfoForm from './components/work-info-form.vue'
import AccountInfoForm from './components/account-info-form.vue'
import { useForm } from '@inertiajs/vue3'

defineOptions({layout: Layout})

const props = defineProps({
    is_client: Boolean
})

const form = useForm({
    account_info: {
        email: '',
        password: ''
    },
    personal_info: {
        first_name: '',
        last_name: '',
        phone_number: '',
        birth_day: '',
    },
    personal_address: {
        region: '',
        province: '',
        city: '',
        barangay: '',
        purok: '',
        complete_address: ''
    },
    work_details: {
        company_name: '',
        company_address: '',
        company_number: '',
        company_email: '',
        length_of_stay: '',
        status: '',
        gross_monthly_income: '',
        job_title: '',
        position: ''
    },
})

const submit = () => {
    form.post()
}
</script>

<template>
    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
        <form-wizard step-size="xs" @on-complete="submit">
            <tab-content title="Account Information" :before-change="validatePersonalAddressData">
                <AccountInfoForm :account_info="form.account_info"/>
            </tab-content>
            <tab-content title="Personal Information" :before-change="validatePersonalInfoData">
               <PersonalInfoForm :personal_info="form.personal_info" />
            </tab-content>
            <tab-content title="Address" :before-change="validatePersonalAddressData">
               <PersonalAddressForm :personal_address="form.personal_address" />
            </tab-content>
            <tab-content title="Work Information" v-if="is_client">
                <WorkInfoForm :work_details="form.work_details" />
            </tab-content>
            
        </form-wizard>
    </div>
</template>