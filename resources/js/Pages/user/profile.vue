<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import PersonalInfoForm from './components/personal-info-form.vue'
import PersonalAddressForm from './components/personal-address-form.vue'
import WorkInfoForm from './components/work-info-form.vue'
import { router, useForm } from '@inertiajs/vue3';
import { useToaster } from '../composables/toast'
import moment from 'moment'
import fileupload from '../components/fileupload.vue'
import Swal from 'sweetalert2'
import Fileupload from '../components/fileupload.vue'

const props = defineProps({
    'user_id': Number
})

const { show } = useToaster()

const form = useForm({
    user_id: props.user_id,
    personal_info: {
        first_name: '',
        last_name: '',
        phone_number: '',
        birth_day: '',
    },
    work_details: {
        company_name: '',
        company_address: '',
        company_number: '',
        company_email: '',
        length_of_stay_year: '',
        length_of_stay_month: '',
        status: '',
        gross_monthly_income: '',
        job_title: '',
        position: ''
    },
    personal_address: {
        region: '',
        province: '',
        city: '',
        barangay: '',
        purok: '',
        complete_address: ''
    },
    file: {}
})
const validatePersonalInfoData = () => {
    return validateData(form.personal_info)
}

const validatePersonalAddressData = () => {
    return validateData(form.personal_address)
}
const validateData = (field) => {
    const hasEmptyField = Object.values(field).some(value => value === '');
    if (hasEmptyField) {
        Swal.fire({
                title: "Opps!",
                text: "Please fill out all the field!",
                icon: "error"
            });
        return false
    }
    return true
}

const submit = () => {


    form.transform((data) => {
        data.personal_address.province = data.personal_address.province.province_name;
        data.personal_address.city = data.personal_address.city.city_name;
        data.personal_address.barangay = data.personal_address.barangay.brgy_name;
        data.personal_address.region = data.personal_address.region.region_name;
        data.work_details.status = data.work_details.status.status;
        data.personal_info.birth_day = moment(data.personal_info.birth_day).format('YYYY-MM-DD');
        data.personal_info.user_id = data.user_id,
        data.personal_address.user_id = data.user_id,
        data.work_details.user_id = data.user_id

        return data;
    }).post('/users',{
        onError: ((err) => {
            console.log(err)
        }),
        onSuccess: ((res) => {
            Swal.fire({
                title: "Success",
                text: "User profile created succesfully!",
                icon: 'success'
            })
            router.get('/properties')
        })
    })
}
defineOptions({layout: Layout})

</script>


<template>
    <Header  :displayBtn="false" :title="'Profile Details'" />
    <Toast />
    <div class="mx-auto bg-white p-8 my-8 rounded shadow-md">
        <form-wizard step-size="xs" @on-complete="submit">
            <tab-content title="Personal Information" :before-change="validatePersonalInfoData">
               <PersonalInfoForm :personal_info="form.personal_info" />
            </tab-content>
            <tab-content title="Address" :before-change="validatePersonalAddressData">
               <PersonalAddressForm :personal_address="form.personal_address" />
            </tab-content>
            <tab-content title="Work Information">
                <WorkInfoForm :work_details="form.work_details" />
            </tab-content>

            <tab-content title="Upload Proof of Income">
                <Fileupload :file="form.file" />
            </tab-content>
        </form-wizard>
    </div>
</template>
