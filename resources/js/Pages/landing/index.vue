<script setup>
import { router } from "@inertiajs/vue3"
import Header from './components/header.vue'
import Contact from './components/contact.vue'
import Footer from './components/footer.vue'
const props = defineProps({
    properties: Object
})

const fetchProperty = (id) => {
    router.get('view-property/' + id)
}


</script>

<template>
  <Header />

  <!-- Hero Section -->
  <section class="relative bg-lime-500 text-white py-20 bg-cover bg-center" style="background-image: url('/bg-logo.jpg');">
    <div class="container mx-auto px-6 text-center">
      <!-- Logo in Hero Section -->
      <div class="mb-8">
        <img src="/bg-logo.jpg" alt="RealEstateApp Logo" class="mx-auto h-20">
      </div>
      <h2 class="text-4xl font-semibold">Find Your Dream Home</h2>
      <p class="mt-4 text-xl">Browse a wide range of properties at your fingertips</p>
      <a href="#properties" class="mt-6 inline-block bg-white text-green-600 py-3 px-8 rounded-full text-lg hover:bg-gray-200 transition">Start Searching</a>
    </div>
  </section>

  <!-- Property Listings -->
  <section id="properties" class="py-16 bg-white">
    <div class="container mx-auto px-6 text-center">
      <h3 class="text-3xl font-semibold text-green-600">Featured Properties</h3>
      <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Property Card -->
        <div class="bg-gray-100 rounded-lg shadow-lg overflow-hidden transform transition-transform duration-300 hover:translate-y-2" v-for="property in properties" :key="property.id">
          <img :src="'/storage/'+property.file.url" alt="Property Image" class="w-full h-56 object-cover">
          <div class="p-6">
            <h4 class="text-xl font-semibold text-green-600">
              Phase {{ property.phase }} Purok {{ property.purok }} Barangay {{ property.barangay }} {{ property.city }} {{ property.province }}
            </h4>
           
            <a href="#" @click.prevent="fetchProperty(property.id)" class="mt-4 inline-block text-green-600 font-semibold hover:underline">View Details</a>
          </div>
        </div>
      </div>
    </div>

  </section>
  <Contact />
  <Footer />
 

 
</template>