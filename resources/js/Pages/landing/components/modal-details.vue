<script setup>
import { defineExpose, ref } from "vue";
import { router } from "@inertiajs/vue3";
import Landmark from "../../property/components/landmark.vue";

const showModal = ref(false);
const props = defineProps({
  property: Object,
});

const closeModal = () => {
  showModal.value = false;
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat("en-PH", {
    style: "currency",
    currency: "PHP",
  }).format(value);
};

const redirect = () => {
  router.get("/login");
};

defineExpose({ showModal });
</script>

<template>
  <!-- Modal -->
  <div
    v-if="showModal"
    class="tw-fixed tw-inset-0 tw-flex tw-items-center tw-justify-center tw-z-50 tw-bg-black tw-bg-opacity-50"
  >
    <div
      class="tw-relative tw-bg-[#FDFFE8] tw-rounded-lg tw-w-full md:tw-w-[700px] tw-max-h-[90vh] tw-overflow-y-auto tw-p-6"
    >
      <!-- Close Button -->
      <button
        @click="closeModal"
        class="tw-absolute tw-top-4 tw-right-4 tw-bg-[#FDFFE8] tw-text-black tw-rounded-full tw-p-2 hover:tw-bg-gray-300 hover:tw-text-gray-800 focus:tw-outline-none"
      >
        &times;
      </button>

      <!-- Modal Content -->
      <div>
        <!-- Logo -->
        <div class="tw-flex tw-items-center tw-justify-center tw-mb-4">
          <img
            src="/Sidebar Logo.png"
            alt="RealEstateApp Logo"
            class="tw-h-12 tw-w-auto"
          />
        </div>

        <!-- Responsive Header Image -->
        <img
          :src="'/storage/app/public/' + property.files[0]?.url"
          alt="Project Image"
          class="tw-w-full tw-h-auto tw-max-h-[400px] tw-rounded-lg tw-mb-2"
        />

        <!-- Header -->
        <div class="tw-mx-auto tw-px-6 tw-text-center">
          <link
            href="https://fonts.googleapis.com/css2?family=Ultra&display=swap"
            rel="stylesheet"
          />
          <h2 class="modal-datail-title">
            Phase {{ property.phase }} -
            <span class="green-text">{{ property.barangay }} Project</span>
          </h2>
          <p class="tw-text-gray-600 tw-mb-4 tw-text-[20px]">
            {{ property.barangay }}, {{ property.city }}, {{ property.province }}
          </p>
        </div>

        <!-- Details Grid and Lot Sizes & Payments Side by Side -->
<div class="tw-grid tw-mb-6">
  <!-- Landmark Section -->
  <div class="tw-grid tw-mb-6">
    <Landmark :property="property" />
  </div>

  <!-- Lot Sizes and Payments -->
  <div class="tw-grid tw-grid-cols-2 tw-gap-4">
    <div>
      <h3 class="tw-font-bold tw-text-md tw-text-gray-800 tw-text-center">
        Size
      </h3>
      <p
        class="tw-flex tw-items-center"
        v-for="lot_group in property.lot_groups"
        :key="lot_group.id"
      >
        <img
          src="/Home.png"
          alt="House Icon"
          class="tw-mr-5 tw-h-5 tw-w-5"
        />
        {{ lot_group.sqr_meter }} sqm
      </p>
    </div>

    <div>
      <h3 class="tw-font-bold tw-text-sm tw-text-gray-800 tw-text-center">
        Monthly Amortization/ <br />Down Payment
      </h3>
      <p v-for="lot_group in property.lot_groups" :key="lot_group.id">
        {{ formatCurrency(lot_group.monthly_amortizations) }}
      </p>
    </div>

    <!-- Description -->
    <div class="tw-col-span-2">
      <h3 class="tw-font-bold tw-text-gray-800 tw-mb-1">Description:</h3>
      <p class="tw-text-gray-600">{{ property.description }}</p>
    </div>
  </div>
</div>
        <!-- Action Buttons -->
        <div class="tw-mt-6 tw-flex tw-justify-between">
          <button
            class="tw-bg-[#093E00] tw-text-white tw-py-2 tw-px-6 tw-rounded-md hover:tw-bg-[#072A00]"
          >
            Click the map marker to view property details
          </button>
          <button
            class="tw-bg-[#FFB800] tw-text-white tw-py-2 tw-px-6 tw-rounded-md hover:tw-bg-[#e6a400]"
            @click="redirect"
          >
            View Lots
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Optional: Smooth Modal Transition */
.tw-bg-opacity-50 {
  transition: opacity 0.3s ease-in-out;
}
</style>
