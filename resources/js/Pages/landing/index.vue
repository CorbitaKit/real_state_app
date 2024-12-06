<script setup>
import { router } from "@inertiajs/vue3"
import Header from './components/header.vue'
import Contact from './components/contact.vue'
import Footer from './components/footer.vue'
import { onMounted, ref, watch } from "vue";
import axios from 'axios'
const props = defineProps({
    properties: Object
})

const filterText = ref()
const debounceDelay = 500;
let debounceTimer = null;
const propertiesData = ref()
const fetchProperty = (id) => {
    router.get('view-property/' + id)
}

onMounted(() => {
  propertiesData.value = props.properties
})

watch(filterText, async (newFilter, oldFilter) => {
  clearTimeout(debounceTimer);

 
  debounceTimer = setTimeout(() => {
    if (!newFilter) {
      propertiesData.value = props.properties
      return
    }
    axios.get('filter-properties/' + newFilter)
    .then(response=> {
      propertiesData.value = response.data
    })
    
  }, debounceDelay);
})

</script>

<template>
  <Header />

  <!-- Hero Section -->
  <section class="tw-relative tw-text-white tw-py-20 tw-bg-cover tw-bg-center" style="background-image: url('/IMG_4702.jpeg');">
    <div class="tw-container tw-mx-auto tw-px-6 tw-text-center">
      <!-- Logo in Hero Section -->
      <div class="tw-mb-8">
        <img src="/Sidebar Logo.png" alt="RealEstateApp Logo" class="tw-mx-auto tw-h-60">
      </div>
      <h2 class="tw-text-4xl tw-text-white tw-font-bold">Find Your Dream Home</h2>
      <p class="tw-mt-4 tw-text-xl tw-text-white tw-font-bold">Browse a wide range of properties at your fingertips</p>
      <a href="#properties" class="tw-mt-6 tw-inline-block tw-bg-white tw-text-green-600 tw-py-3 tw-px-8 tw-rounded-full tw-text-lg tw-hover:bg-gray-200 tw-transition">Start Searching</a>
    </div>
  </section>

  <!-- Property Listings -->
  <section id="properties" class="tw-py-16 tw-bg-white">
    <div class="tw-flex tw-justify-center tw-mb-8 tw-w-full">
        <input
          v-model="filterText"
          type="text"
          placeholder="Filter by address (e.g., Purok, Barangay, City, Provice)"
          class="tw-border tw-border-gray-300 tw-px-4 tw-py-2 tw-rounded-md tw-w-full md:tw-w-1/2 lg:tw-w-1/3"
        />
      </div>

    <div class="tw-container tw-mx-auto tw-px-6 tw-text-center">
      <h3 class="tw-text-3xl tw-font-semibold tw-text-green-600">Featured Properties</h3>
      <div class="tw-mt-8 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-8">
        <!-- Property Card -->
        <div class="tw-bg-gray-100 tw-rounded-lg tw-shadow-lg tw-overflow-hidden tw-transform tw-transition-transform tw-duration-300 hover:tw-translate-y-2" v-for="property in propertiesData" :key="property.id">
          <img :src="'/storage/'+property.file.url" alt="Property Image" class="tw-w-full tw-h-56 tw-object-cover">
          <div class="tw-p-6">
            <h4 class="tw-text-xl tw-font-semibold tw-text-green-600">
              Phase {{ property.phase }} Purok {{ property.purok }} Barangay {{ property.barangay }} {{ property.city }} {{ property.province }}
            </h4>
           
            <a href="#" @click.prevent="fetchProperty(property.id)" class="tw-mt-4 tw-inline-block tw-text-green-600 tw-font-semibold tw-hover:underline">View Details</a>
          </div>
        </div>
      </div>
    </div>

  </section>
  <Contact />
  <Footer />
 

 
</template>