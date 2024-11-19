<script setup>
  import Header from './components/header.vue'
  import Contact from './components/contact.vue'
  import Footer from './components/footer.vue'

  const props = defineProps({
    property: Object,
    required: true
  })

  const formatPrice = (price) => {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
</script>


<template>
  <Header />
  <div class="container mx-auto px-6 py-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
      <!-- Property Image -->
      <div>
        <img :src="'/storage/' + property.file.url" alt="Property Image" class="w-full h-64 object-cover rounded-lg shadow-lg mb-6">
          <DataTable showGridlines  :value="property.lot_groups" tableStyle="min-width: 30rem">
            <Column field="sqr_meter" header="Square Meter">
              <template #body="slotProps">
                {{ slotProps.data.sqr_meter }} m&sup2;
              </template>
            </Column>
            <Column field="amount_per_sqr_meter" header="Amount per square meter">
              <template #body="slotProps">
                ₱{{ formatPrice(slotProps.data.amount_per_sqr_meter) }}
              </template>
            </Column>
            <Column field="monthly_amortizations" header="Monthly Amortization">
              <template #body="slotProps">
                ₱{{ formatPrice(slotProps.data.monthly_amortizations) }}
              </template>
            </Column>
        </DataTable>
      </div>

      <!-- Map Section -->
      <div class="relative w-full h-64">
        <iframe 
          width="100%" 
          height="100%" 
          class="rounded-lg shadow-lg" 
          :src="mapSrc" 
          frameborder="0" 
          style="border:0;" 
          allowfullscreen
          aria-hidden="false"
          tabindex="0"
        ></iframe>
      </div>
    </div>
    
    <div class="mt-8">
      <h3 class="text-2xl font-semibold text-green-600">Location</h3>
      <p class="text-gray-700">Phase {{ property.phase }}, Purok {{ property.purok }}, Barangay {{ property.barangay }}, {{ property.city }}, {{ property.province }}</p>
    </div>
  </div>
  
  <Contact />
  <Footer />
</template>

