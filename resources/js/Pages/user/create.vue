<script setup>
import Layout from '../layout/main.vue'
import PersonalInfoForm from './components/personal-info-form.vue'
import PersonalAddressForm from './components/personal-address-form.vue'
import WorkInfoForm from './components/work-info-form.vue'
import AccountInfoForm from './components/account-info-form.vue'
import { useForm, router } from '@inertiajs/vue3'
import moment from 'moment'
import Swal from 'sweetalert2'
import { ref } from 'vue'
import fileupload from '../components/fileupload.vue'
defineOptions({layout: Layout})

const props = defineProps({
    is_client: Boolean
})

const account_info_error = ref(false)
const personal_info_error = ref(false)
const personal_address_error = ref(false)
const is_skip = ref(false)
const form = useForm({
    file: {},
    account_info: {
        email :'',
        password:''
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
        company_name: !props.is_client ? 'Aldebal Real State Service' : '',
        company_address: '',
        company_number: '',
        company_email: '',
        length_of_stay: '',
        status: '',
        gross_monthly_income: !props.is_client ? '0000.00' : '',
        job_title: '',
        position: ''
    },
})

const submit = () => {
    if (is_skip) {
        form.work_details.company_name = 'N/A'
        form.work_details.company_address = 'N/A'
        form.work_details.company_number = 'N/A'
        form.work_details.company_email = 'N/A'
        form.work_details.length_of_stay = 0
        form.work_details.position = 'N/A'
        form.work_details.gross_monthly_income = 0.00
        form.work_details.job_title = 'N/A'
    }
    form.transform((data) => {
        data.personal_address.province = data.personal_address.province.province_name;
        data.personal_address.city = data.personal_address.city.city_name;
        data.personal_address.barangay = data.personal_address.barangay.brgy_name;
        data.personal_address.region = data.personal_address.region.region_name;
        data.work_details.status = !is_skip ? data.work_details.status.status : 'N/A' ;
        data.account_info.role_id = props.is_client ? 3 : 2;
        data.personal_info.birth_day = moment(data.personal_info.birth_day).format('YYYY-MM-DD');
        return data;
    }).post('/users',{
        onError: ((err) => {
            console.log(err)
        }),
        onSuccess: ((res) => {

            if (!props.is_client) {
                Swal.fire({
                    title: "Success!",
                    text: "Staff Created Successfully!",
                    icon: "success"
                });
                router.get('/users')
            } else {
                Swal.fire({
                    title: "Success!",
                    text: "Client Created Successfully!",
                    icon: "success"
                });
                router.get('/clients')
            }
        })
    })
}

const validatePersonalInfoData = () => {
    const dateToCheck = new Date(form.personal_info.birth_day);

    // Get the current date
    const today = new Date();

    // Calculate the age
    let age = today.getFullYear() - dateToCheck.getFullYear();
    const monthDifference = today.getMonth() - dateToCheck.getMonth();
    const dayDifference = today.getDate() - dateToCheck.getDate();

    // Adjust age if the current date is before the birthdate this year
    if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
    age--;
    }

    if (age <= 17) {
        Swal.fire({
            title: "Opps!",
            text: "The age should be 18 and up!",
            icon: "error"
        });
        return false
    }

    const hasEmptyField = checkIfFieldsAreFilled(form.personal_info)

    if (hasEmptyField) {
        personal_info_error.value = true
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please fill out all fields",
        });
        return false;
    }
        return true;

    
}
const checkIfFieldsAreFilled = (data) => {
   
    return  Object.keys(data).some(key => {
        return !data[key];
    });


    
}

const validateAccountInfoData = () => {
    const hasEmptyField = checkIfFieldsAreFilled(form.account_info)
    if (hasEmptyField) {
        account_info_error.value = true
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please fill out all fields",
        });
        return false;
    }
        return true;
}

const validatePersonalAddressData = () => {
    const hasEmptyField = checkIfFieldsAreFilled(form.personal_address)
    if (hasEmptyField) {
        personal_address_error.value = true
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please fill out all fields",
        });
        return false;
    }
        return true;
}

</script>

<template>
   
    <div class="row">
        
        <div class="col-md-12 mb-2">
            <!-- begin page title -->
            <div class="d-block d-sm-flex flex-nowrap align-items-center">
                <div class="page-title mb-2 mb-sm-0">
                    <h1 v-if="!is_client">Create Staff</h1>
                    <h1 v-else>Create Client</h1>
                </div>
                
                <div class="ml-auto d-flex align-items-center">
                    
                    <nav>
                        <ol class="breadcrumb p-0 m-b-0">
                            <li class="breadcrumb-item">
                                <a href="#" @click="home('/dashboard')"><i class="ti ti-home"></i></a>
                            </li>
                            
                            <li v-if="!is_client" class="breadcrumb-item active text-primary" aria-current="page">Create Staff</li>
                            <li v-else class="breadcrumb-item active text-primary" aria-current="page">Create Client</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- end page title -->
        </div>
    </div>
    <div class="tw-mx-auto tw-bg-white tw-p-8 tw-my-8 tw-rounded tw-shadow-md">
        <form-wizard step-size="xs" @on-complete="submit">
            <tab-content title="Account Information" :before-change="validateAccountInfoData">
                <AccountInfoForm :account_info="form.account_info" :error="account_info_error"/>
            </tab-content>
            <tab-content title="Personal Information" :before-change="validatePersonalInfoData">
               <PersonalInfoForm :personal_info="form.personal_info" :error="personal_info_error" />
            </tab-content>
            <tab-content title="Address" :before-change="validatePersonalAddressData">
               <PersonalAddressForm :personal_address="form.personal_address" :error="personal_address_error" />
            </tab-content>
            <tab-content title="Work Information" v-if="!is_skip">
                
                <WorkInfoForm :work_details="form.work_details" @skip="is_skip = true" />
            </tab-content>
            <tab-content title="Requirements" v-if="is_client">
                
                <fileupload :file="form.file" />
            </tab-content>
            
        </form-wizard>
    </div>
</template>