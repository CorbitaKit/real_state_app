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
    <!-- <div class="tw-grid tw-grid-cols-2 tw-gap-4 tw-mx-9">
        <div class="tw-mb-4">
            <label for="region" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Region</label>
            <Dropdown @change="handleProvinces" v-model="personal_address.region" :options="regions" filter optionLabel="region_name" placeholder="Select a region" class="tw-w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="region" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Province</label>
            <Dropdown @change="handleCities" v-model="personal_address.province" :options="provinces" filter optionLabel="province_name" placeholder="Select a province" class="w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="region" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">City</label>
            <Dropdown @change="handleBarangays" v-model="personal_address.city" :options="cities" filter optionLabel="city_name" placeholder="Select a city" class="w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="region" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Barangay</label>
            <Dropdown v-model="personal_address.barangay" :options="barangays" filter optionLabel="brgy_name" placeholder="Select a city" class="w-full md:w-full border" />
        </div>
        <div class="mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Purok</label>
            <InputText type="text" v-model="personal_address.purok" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
                
        </div>
        <div class="mb-4">
            <label for="purok" class="tw-block tw-text-gray-700 tw-font-semibold tw-mb-2">Complete Address</label>
            <InputText type="text" v-model="personal_address.complete_address" class="block appearance-none w-full px-3 py-2 border rounded-md"/>        
                
        </div>
    </div> -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-statistics">
                <div class="card-header">
                    <div class="card-heading">
                        <h5 class="card-title">Property Address</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Region</label>
                                <select  class="js-basic-single form-control" name="region" v-model="personal_address.region" @change="handleProvinces">
                                    <option  v-for="region in regions" :value="region">{{ region.region_name }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Province</label>
                                <select  class="js-basic-single form-control" name="province"v-model="personal_address.province" @change="handleCities">
                                    <option  v-for="province in provinces" :value="province">{{ province.province_name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">City</label>
                                <select  class="js-basic-single form-control" name="city" v-model="personal_address.city" @change="handleBarangays">
                                    <option  v-for="city in cities" :value="city">{{ city.city_name }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Barangay</label>
                                <select  class="js-basic-single form-control" name="province" v-model="personal_address.barangay" @change="handleCities">
                                    <option  v-for="brgy in barangays" :value="brgy">{{ brgy.brgy_name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputCity">Purok</label>
                                <input type="text" class="form-control" id="phase" v-model="personal_address.purok" >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputCity">Complete Address</label>
                                <input type="text" class="form-control" id="phase" v-model="personal_address.complete_address" >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>