<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import Card from './components/card.vue'
import { router } from '@inertiajs/vue3'
import {getUser} from '../plugins/get-user-plugin'
const { getUserInfo } = getUser()


const user = getUserInfo()

const props = defineProps({
    properties: Object
})

const setButton = () => {
    if (user.role_id === 3 || user.role_id === 2) {
        return false
    }
    return true
}
const submit = () => {
    router.get('/properties/create')
}
defineOptions({layout: Layout})
</script>


<template>
    <Header @submit="submit" :title="'Property Page'" :displayBtn="setButton()" :btnTxt="'Create Property'" />
    <div class="grid grid-cols-3 gap-4 mt-5">
       <Card v-for="property in properties" :key="property.id" :property="property" :displayBtn="true"/>
    </div>
</template>