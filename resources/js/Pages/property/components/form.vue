<script setup>
// import {regions, provinces, cities, barangays} from 'select-philippines-address';
import { onMounted, ref } from 'vue';
import { usePropertyStore } from '../store/store'
import { generateRegion, handleCity, handleProvince, handleBarangay } from '../../composables/address'

const useProperty = usePropertyStore()


const provinces = ref([])
const regions = ref([])
const cities = ref([])
const barangays = ref([])

onMounted(async () => {
    regions.value = await generateRegion()
})

const handleProvinces = async () => {
    provinces.value = await handleProvince(useProperty.property.region.region_code)
}

const handleCities = async () => {
    cities.value = await handleCity(useProperty.property.province.province_code)
}

const handleBarangays = async () => {
    barangays.value = await handleBarangay(useProperty.property.city.city_code)
}


</script>


<template>
    <div class="grid grid-cols-2 gap-4 mx-9">
        
        <div class="mb-4">
            <label for="region" class="block text-gray-700 font-semibold mb-2">Region</label>
            <Dropdown v-model="useProperty.property.region" @change="handleProvinces" :options="regions" filter optionLabel="region_name" placeholder="Select a region" class="w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="province" class="block text-gray-700 font-semibold mb-2">Province</label>
            <Dropdown v-model="useProperty.property.province" @change="handleCities" :options="provinces" filter optionLabel="province_name" placeholder="Select a province" class="w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="city" class="block text-gray-700 font-semibold mb-2">City</label>
            <Dropdown v-model="useProperty.property.city" @change="handleBarangays" :options="cities" filter optionLabel="city_name" placeholder="Select a city" class="w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="barangay" class="block text-gray-700 font-semibold mb-2">Barangay</label>
            <Dropdown v-model="useProperty.property.barangay" :options="barangays" filter optionLabel="brgy_name" placeholder="Select a barangay" class="w-full md:w-full border" />

        </div>
        <div class="mb-4">
            <label for="phase" class="block text-gray-700 font-semibold mb-2">Phase</label>
            <InputText type="text" v-model="useProperty.property.phase" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Purok</label>
            <InputText type="text" v-model="useProperty.property.purok" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
                
        </div>
    </div>
</template>