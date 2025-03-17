<script setup>
import { defineEmits } from "vue";

const props = defineProps({
    properties: Object
})
// Emit setup
const emit = defineEmits(["open-details"]);

const openModal = (property) => {
  emit("open-details", property);
};
</script>


<template>
  <section id="body" class="tw-py-16 tw-bg-[#FCE35F40] tw-text-gray-900">
    <!-- Container -->
    <div class=" tw-mx-auto tw-px-6 tw-text-center">
    <div class=" tw-mx-auto tw-px-6 tw-text-center">
      <link href="https://fonts.googleapis.com/css2?family=Ultra&display=swap" rel="stylesheet">
      <h2 class="property-title">
        Property List
      </h2>
    </div>
      <!-- Heading -->
      <h3 class="view-details-button">
        View Full Details >>
      </h3>

      <!-- Scrollable Card Container -->
      <div class="row">
        <div class="col-md-4"  v-for="property in properties" :key="property.id">
            <div class="tw-overflow-x-auto tw-whitespace-nowrap tw-mt-6">
                <div class="tw-inline-flex tw-gap-6">
                <!-- Card 1 -->
                <div
                    class="tw-bg-white tw-rounded-lg tw-p-4 tw-text-left tw-w-100 tw-shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-1px_rgba(0,0,0,0.06)]"

                >
                    <img :src="'/storage/app/public/'+property.files[0]?.url" alt="Phase 1 Image" class="tw-rounded-md tw-mb-4" />
                    <h4 class="tw-text-lg tw-font-semibold tw-mb-2">
                        Phase {{ property.phase }} - {{ property.barangay }} Project
                    </h4>
                    <p class="tw-text-gray-600 tw-text-sm">
                    {{ property.barangay }}, {{ property.city }}, {{ property.province }}
                    </p>
                    <button
                    @click="openModal(property)"
                    class="tw-mt-4 tw-bg-[#FFB800] tw-text-white tw-font-medium tw-py-2 tw-w-full tw-rounded-md hover:tw-bg-[#e6a400] tw-shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-1px_rgba(0,0,0,0.06)]"
                    >
                    View Details
                    </button>
                </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style>
.view-details-button {
  color: #093E00;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  text-align: end;
}
</style>
