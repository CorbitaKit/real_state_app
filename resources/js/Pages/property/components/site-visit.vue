<script setup>
import { useForm } from "@inertiajs/vue3"
import moment from "moment"
import {getUser} from '../../plugins/get-user-plugin'


const { getUserInfo } = getUser()

const user = getUserInfo()

const emits = defineEmits(['siteVisit'])

const form = useForm({
    application_type: 'Lot Application',
    lot_id: 0,
    user_id: user.id,
    reserved_date: '',
    status: 'For Review'
})

const submit = () => {
    form.transform((data) => ({
    ...data,
    reserved_date:  moment(data.reserved_date).format('YYYY-MM-DD')
  })).post('/applications', {
    onSuccess: (() => {
        emits('siteVisit')
    })
  })
}

</script>


<template>
    <span class="text-surface-500 dark:text-surface-400 block mb-8">Set a date for your site visit.</span>
    <div class="flex items-center gap-4 mb-4">
        <label for="username" class="font-semibold w-24">Date</label>
        <Calendar v-model="form.reserved_date" class="w-full rounded-md"/>
    </div>
    <div class="flex justify-end gap-2">
        <Button type="button" label="Save" @click="submit"></Button>
    </div>
</template>