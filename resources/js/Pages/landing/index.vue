<script setup>
import { router } from "@inertiajs/vue3"
import Header from './components/header.vue'
import Contact from './components/contact.vue'
import Footer from './components/footer.vue'
import Body from './components/body.vue'
import ModalDetail from './components/modal-details.vue'
import About from './components/about.vue'
import { onMounted, ref, watch } from "vue";
import axios from 'axios'

const props = defineProps({
    properties: Object
})

const modalRef = ref(); // Modal reference
const filterText = ref("");
const debounceDelay = 500;
let debounceTimer = null;

// Fetch and display properties
const propertiesData = ref(props.properties);

const openDetailsModal = () => {
  modalRef.value.showModal = true;
};

watch(filterText, () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    if (!filterText.value) {
      propertiesData.value = props.properties;
      return;
    }

    axios.get(`filter-properties/${filterText.value}`).then((response) => {
      propertiesData.value = response.data;
    });
  }, debounceDelay);
});


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
  <section class="tw-relative tw-text-white tw-py-[200px] tw-bg-cover tw-bg-center" style="background-image: url('/black-balay.png');">
   <div class="w-full h-full tw-z-50 tw-bg-black tw-bg-opacity-50"></div>
    <div class="tw-container tw-mx-auto tw-px-6 tw-text-center ">
      <link href="https://fonts.googleapis.com/css2?family=Ultra&display=swap" rel="stylesheet">
      <h2 class="introduction-title">
        Discover, <span class="green-text">Dream, Own</span>
      </h2>
      <p class="tw-text-xl tw-text-white ">Your dream property is closer than you think</p>
    </div>
  

   <!-- Search Bar Section -->
<div class="tw-flex tw-justify-center tw-mt-4">
  <div class="tw-relative tw-w-full md:tw-w-1/2 lg:tw-w-1/3">
    <input
      v-model="filterText"
      type="text"
      placeholder="Search Address, City, Zip Code, Phase"
      class="tw-w-full tw-border tw-border-gray-300 tw-rounded-full tw-py-3 tw-pl-10 tw-pr-10 tw-text-gray-600 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-green-600"
    />
    <span class="tw-absolute tw-inset-y-0 tw-left-0 tw-flex tw-items-center tw-pl-4">
      <!-- Search Icon -->
      <svg class="tw-w-5 tw-h-5 tw-text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.3-6.15a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </span>
    <span class="tw-absolute tw-inset-y-0 tw-right-0 tw-flex tw-items-center tw-pr-4">
      <!-- Filter Button -->
      <button type="button" class="tw-text-gray-600 tw-hover:text-green-600">
        <!-- New Filter Icon -->
        <svg class="tw-w-5 tw-h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L14 12.414V19a1 1 0 01-.293.707l-4 4a1 1 0 01-1.414-1.414L12 19.586V12.414L3.293 6.707A1 1 0 013 6V4z" />
        </svg>
      </button>
    </span>
  </div>
</div>
</section>

  <!-- Body Component -->
  <Body @open-details="openDetailsModal" />

  <!-- Modal Component -->
  <ModalDetail ref="modalRef" />

  <About />
  
  <Contact />
  <Footer />
</template>

<style>
.introduction-title {
  font-family: 'Ultra', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 64px;
  line-height: 70px;

  /* Text Styling */
  color: #E6AC35; /* Yellow color for "Discover" */
  
  /* White border effect */
  text-shadow: 
    -1px -1px 0 #ffffff, /* Top-left */
     1px -1px 0 #ffffff, /* Top-right */
    -1px  1px 0 #ffffff, /* Bottom-left */
     1px  1px 0 #ffffff, /* Bottom-right */
     0px  0px 0px #ffffff; /* Optional glow effect */

  display: inline-block;
}

.green-text {
  color: #114B2A; /* Dark green color for "Dream, Own" */
}

.modal-datail-title {
  font-family: 'Ultra', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 38px;
  line-height: 50px;

  /* Text Styling */
  color: #E6AC35; /* Yellow color for "Discover" */
  
  /* White border effect */
  text-shadow: 
    -1px -1px 0 #ffffff, /* Top-left */
     1px -1px 0 #ffffff, /* Top-right */
    -1px  1px 0 #ffffff, /* Bottom-left */
     1px  1px 0 #ffffff, /* Bottom-right */
     0px  0px 0px #ffffff; /* Optional glow effect */

  display: inline-block;
}

.green-text {
  color: #114B2A; /* Dark green color for "Dream, Own" */
}

.property-title {
  font-family: 'Ultra', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 40px;
  line-height: 50px;

  /* Text Styling */
  color: #E6AC35; /* Yellow color for "Discover" */
  
  /* White border effect */
  text-shadow: 
    -1px -1px 0 #000000, /* Top-left */
     1px -1px 0 #000000, /* Top-right */
    -1px  1px 0 #000000, /* Bottom-left */
     1px  1px 0 #000000, /* Bottom-right */
     0px  0px 0px #000000; /* Optional glow effect */

  display: inline-block;
}
.about-title {
  font-family: 'Ultra', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 40px;
  margin-bottom: 20px;
  text-align: center;

  /* Text Styling */
  color: #27542B; /* Yellow color for "Discover" */
  
  /* White border effect */
  text-shadow: 
    -1px -1px 0 #FCFFFC, /* Top-left */
     1px -1px 0 #FCFFFC, /* Top-right */
    -1px  1px 0 #FCFFFC, /* Bottom-left */
     1px  1px 0 #FCFFFC, /* Bottom-right */
     0px  0px 0px #FCFFFC; /* Optional glow effect */
}
.our-title {
  font-family: 'Ultra', sans-serif;
  font-style: normal;
  font-weight: 200;
  font-size: 30px;
  margin-bottom: 20px;
  text-align: center;

  /* Text Styling */
  color: #FCFFFC; /* Yellow color for "Discover" */
  
}
.contact-title {
  font-family: 'Ultra', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 40px;
  line-height: 51.31px;
  text-align: left;

  /* Text Styling */
  color: #E7AB2D; /* Yellow color for "Discover" */
  
}
.touch-title {
  font-family: 'Montserrat', sans-serif;
  font-style: normal;
  font-weight: 800;
  font-size: 24px;
  margin-bottom: 20px;
  text-align: left;

  /* Text Styling */
  color: #FCFFFC; /* Yellow color for "Discover" */
  
}
.info-title {
  font-family: 'Montserrat', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 24px;
  line-height: 29.26px;
  text-align: left;

  /* Text Styling */
  color: #FCFFFC; /* Yellow color for "Discover" */
  
}

</style>