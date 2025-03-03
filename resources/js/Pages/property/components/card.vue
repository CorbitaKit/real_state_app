<script setup>
import { router } from "@inertiajs/vue3"


const props = defineProps({
    property: Object,
    displayBtn: true
})


const fetchProperty = () => {
    router.get('/properties/' + props.property.id)
}
</script>

<template>
    <div class="card">
        <a :href="'/storage/app/public/'+property.file.url" target="__blank" class="group relative block overflow-hidden">
            <img :src="'/storage/app/public/'+property.file.url" alt="" class="w-full object-cover transition duration-500 group-hover:scale-105 sm:h-72"/>
        </a>
        <div class="relative border border-gray-100 bg-white p-6">
            <h3 class="mt-4 text-lg font-medium text-gray-900">Phase {{ property.phase }}</h3>
            <p class="mt-1.5 bolder font-medium text-md text-gray-700">Purok {{ property.purok }} {{ property.barangay }}, {{ property.city }} City, </p>
            <p class="mt-1.5 bolder font-medium text-md text-gray-700">{{ property.province }}</p>
            <p class="mt-1.5 bolder font-medium text-md text-gray-700">Down Payment: {{ property.down_payment }}</p>
            <p class="mt-1.5 bolder font-medium text-md text-gray-700">Balance Payable in: {{ property.balance_payable }}</p>

            <form class="mt-4" v-if="displayBtn">
                <button @click.stop="fetchProperty" type="button" class="block w-full rounded bg-yellow-400 p-4 text-sm font-medium transition hover:scale-105">
                    View Details
                </button>
            </form>
        </div>
    </div>
</template>
