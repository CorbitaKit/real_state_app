<script setup>
import Layout from '../layout/main.vue'
import Header from '../components/header.vue'
import Card from './components/card.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    properties: Object
})
const submit = () => {
    router.get('/properties/create')
}
defineOptions({layout: Layout})
</script>


<template>
    <Header @submit="submit" :title="'Property Page'" :btnTxt="'Create Property'" />
    <div class="grid grid-cols-3 gap-4 mt-5">
       <Card v-for="property in properties" :key="property.id" :property="property" :displayBtn="true"/>
    </div>
</template>