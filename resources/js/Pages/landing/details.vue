<script setup>
  import Header from './components/header.vue'
  import Contact from './components/contact.vue'
  import Footer from './components/footer.vue'
  import LandMark from '../property/components/landmark.vue'
  const props = defineProps({
    property: Object,
    required: true
  })

  const formatPrice = (price) => {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
  const setLocation = (property) => {
    return property.purok + ', ' + property.barangay + ', ' + property.city
}
</script>


<template>
  <Header />
  <div class="tw-container tw-mx-auto tw-px-6 tw-py-10">
    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-10">
      <!-- Property Image -->
      <div>
        <img :src="'/storage/app/public/' + property.file.url" alt="Property Image" class="tw-w-[100px] tw-h-[100px] tw-object-cover tw-rounded-lg tw-shadow-lg tw-mb-6">
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
      <div>
        <LandMark :address="setLocation(property)" :key="property.id" :map="property.id" :property="property"/>
      </div>


    </div>


    <div class="tw-mt-8">
      <h3 class="tw-text-2xl tw-font-semibold tw-text-green-600">Location</h3>
      <p class="tw-text-gray-700">Phase {{ property.phase }}, Purok {{ property.purok }}, Barangay {{ property.barangay }}, {{ property.city }}, {{ property.province }}</p>
    </div>
  </div>

  <Contact />
  <Footer />
</template>

