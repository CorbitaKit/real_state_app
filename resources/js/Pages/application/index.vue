<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import {getUser} from '../plugins/get-user-plugin'
import { useForm } from '@inertiajs/vue3'
import { useConfirm } from "primevue/useconfirm";
import { ref } from 'vue';
import { useToaster } from '../composables/toast'


const { getUserInfo } = getUser()
const confirm = useConfirm();
const { show } = useToaster()


const props = defineProps({
    applications: Object
})
const user = getUserInfo()
const visible = ref(false)
const application_id = ref()

const form = useForm({
    status: '',
    reason: ''
})

const handleStatusChange = (status, id) => {
    application_id.value = id
    if (status === 'Approved') {
        confirm.require({
            message: 'Are you sure you want to approve this application?',
            header: 'Confirmation',
            icon: 'pi pi-exclamation-triangle',
            rejectProps: {
                label: 'Cancel',
                severity: 'secondary',
                outlined: true
            },
            acceptProps: {
                label: 'Save'
            },
            accept: () => {
                form.status = status
                sendRequest()
            },
        });
    } else {
        visible.value = true
    }
}

const rejectApplication = () => {
    form.status = 'Rejected'
    sendRequest()
    visible.value = false
}
const sendRequest = () => {
    form.patch('/applications/' + application_id.value, {
        onSuccess: (() => {
            show('success', 'Application update!', 'Email sent to the client')
        })
    })
}
defineOptions({layout: Layout})

</script>

<template>
    <Header  :displayBtn="false" :title="'Application List'" />
    <ConfirmDialog />
    <Toast />
    <Dialog v-model:visible="visible" modal header="Reject Application" :style="{ width: '50rem' }">
        <span class="text-surface-500 dark:text-surface-400 block mb-8">State your reason for rejecting this application.</span>
        <div class="flex items-center gap-4 mb-4">
            <label for="username" class="font-semibold w-24">Reason</label>
            <InputText v-model="form.reason" class="w-full rounded-md"/>
        </div>
        <div class="flex justify-end gap-2">
            <Button type="button" label="Save" @click="rejectApplication"></Button>
        </div>
    </Dialog>
    <div class="relative overflow-x-auto mt-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th v-if="user.role.name == 'Admin'" scope="col" class="px-6 py-3">
                       Applicant
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Application Type
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Reserved Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Lot Address
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th v-if="user.role.name == 'Admin'" scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" v-for="application in applications" :key="application.id">
                    <th v-if="user.role.name == 'Admin'" scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ application.user.personal_info.first_name }} {{ application.user.personal_info.last_name }} 
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ application.application_type }}
                    </th>
                    <td class="px-6 py-4">
                        {{ application.reserved_date }}
                    </td>
                    <td class="px-6 py-4">
                        Phase {{ application.lot.property.phase }},
                        purok {{ application.lot.property.purok }},
                        barangay {{ application.lot.property.barangay }},<br>
                        {{ application.lot.property.city }},
                        {{ application.lot.property.province }},
                       <span v-if="application.type === 'Lot Application'">Lot {{ application.lot.id }} </span> 
                        
                        
                       
                    </td>
                    <td class="px-6 py-4">
                        <Tag v-if="application.status === 'For Review'" severity="info" :value="application.status"></Tag>
                        <Tag v-if="application.status === 'Approved'" severity="success" :value="application.status"></Tag>
                        <Tag v-if="application.status === 'Rejected'" severity="danger" :value="application.status"></Tag>
                    </td>
                    <td class="px-6 py-4" v-if="user.role.name == 'Admin'">
                        <v-icon v-if="application.status === 'For Review'" @click="handleStatusChange('Approved', application.id)" name="fc-approval" animation="ring" scale="2" class="text-4xl rounded cursor-pointer block float-left mr-2"/>
                        <v-icon v-if="application.status === 'For Review'" @click="handleStatusChange('Rejected', application.id)" fill="red" name="fa-window-close" animation="wrench" scale="2" class="text-4xl rounded cursor-pointer block float-left mr-2"/>
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</template>