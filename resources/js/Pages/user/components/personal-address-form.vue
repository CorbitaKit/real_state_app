<script setup>
import { onMounted, ref } from 'vue';
import { generateRegion, handleCity, handleProvince, handleBarangay } from '../../composables/address'


const props = defineProps({
    personal_address: Object
})

const provinces = ref([])
const regions = ref([])
const cities = ref([])
const barangays = ref([])

onMounted(async () => {
    regions.value = await generateRegion()
})

const handleProvinces = async () => {
    provinces.value = await handleProvince(props.personal_address.region.region_code)
}

const handleCities = async () => {
    cities.value = await handleCity(props.personal_address.province.province_code)
}

const handleBarangays = async () => {
    barangays.value = await handleBarangay(props.personal_address.city.city_code)
}


</script>


<template>
    <div class="grid grid-cols-2 gap-4 mx-9">
        <div class="mb-4">
            <label for="region" class="block text-gray-700 font-semibold mb-2">Region</label>
            <Dropdown @change="handleProvinces" v-model="personal_address.region" :options="regions" filter optionLabel="region_name" placeholder="Select a region" class="w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="region" class="block text-gray-700 font-semibold mb-2">Province</label>
            <Dropdown @change="handleCities" v-model="personal_address.province" :options="provinces" filter optionLabel="province_name" placeholder="Select a province" class="w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="region" class="block text-gray-700 font-semibold mb-2">City</label>
            <Dropdown @change="handleBarangays" v-model="personal_address.city" :options="cities" filter optionLabel="city_name" placeholder="Select a city" class="w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="region" class="block text-gray-700 font-semibold mb-2">Barangay</label>
            <Dropdown v-model="personal_address.barangay" :options="barangays" filter optionLabel="brgy_name" placeholder="Select a city" class="w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Purok</label>
            <InputText type="text" v-model="personal_address.purok" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
                
        </div>
        <div class="mb-4">
            <label for="purok" class="block text-gray-700 font-semibold mb-2">Complete Address</label>
            <InputText type="text" v-model="personal_address.complete_address" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
                
        </div>
    </div>
</template>