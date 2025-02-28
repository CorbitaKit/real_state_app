<script setup>
import { ref, onMounted, nextTick } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import axios from 'axios'
import Show from '../show.vue'
import { router } from '@inertiajs/vue3'

const defaultLatLng = [7.3151, 125.6190]
const latLng = ref(defaultLatLng)
const map = ref(null)
const mapContainer = ref(null)
const property = ref(null)
const visible = ref(false)
const props = defineProps({
  address: String,
  map: String,
  property: Object
})

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
      }).format(value);
}
const geocodeAddress = async (address) => {
  try {
    const response = await axios.get('https://nominatim.openstreetmap.org/search', {
      params: {
        q: address,
        format: 'json',
        addressdetails: 1,
      },
    })

    const result = response.data[0]
    if (result) {
      latLng.value = [parseFloat(result.lat), parseFloat(result.lon)]
    } else {
      console.error('Address not found')
      latLng.value = defaultLatLng
    }
  } catch (error) {
    console.error('Geocoding error:', error)
    latLng.value = defaultLatLng
  }
}

const handleLotEvent = (lot) => {
  router.get('/properties/' + lot.property.id)
}


onMounted(() => {
  nextTick(() => {
    property.value = props.property
    map.value = L.map(mapContainer.value).setView(latLng.value, 15)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map.value)

    const marker = L.marker(latLng.value).addTo(map.value)
    marker.bindPopup("Marker Location").openPopup()
    marker.on('click', () => {
      visible.value = true
    })
    if (props.address) {
      geocodeAddress(props.address).then(() => {
        map.value.setView(latLng.value, 20)
        marker.setLatLng(latLng.value)
        marker.bindPopup('Purok ' + props.address).openPopup()
      })
    }

  })
})

const calculateTotalAmount = (lot_group) => {
    return lot_group.sqr_meter * lot_group.amount_per_sqr_meter
}

const calculateRemainingBalance = (lot) => {
    const total_amount = calculateTotalAmount(lot.lot_group)
    const total_payment = lot.payments.reduce((sum, payment) => sum + payment.amount, 0)

    return total_amount - total_payment
}

const calculateTotalPayment = (lot) => {
    return lot.payments.reduce((sum, payment) => sum + payment.amount, 0)
}

</script>

<template>
  <div>
    <div id="map" ref="mapContainer" style="height: 500px;"></div>
    <Dialog v-model:visible="visible" modal header="Lot Details" :style="{ width: '100rem' }">

        <div class="row"  style="display: flex; height: 500px; overflow: hidden;">

    <div class="col-md-6 tw-flex-shrink-0" >
        <Image :src="'/storage/'+property.files[1]?.url" alt="Image" preview class="tw-w-full tw-h-full tw-object-cover"  v-if="property.files[1]"/>
    </div>

    <!-- Scrollable Lots Section -->
    <div class="col-md-6 tw-overflow-y-auto tw-max-h-full">
        <div class="tw-grid tw-grid-cols-2 tw-gap-3 tw-h-full tw-overflow-y-auto tw-pr-2">
            <div class="tw-mb-4 tw-text-xl" v-for="lot in property.lots" :key="lot.id">
                <button style="white-space: nowrap;" @click="handleLotEvent(lot)" type="button"
                    class="tw-text-xl tw-font-medium tw-text-black tw-block tw-w-full tw-rounded tw-p-4 tw-text-sm tw-font-medium transition hover:scale-105"
                    :class="lot.lot_group.color_label">
                    <span>{{ lot.name }}</span><br>
                    <span>Square Meter: {{ lot.lot_group.sqr_meter }} sq m&sup2;</span><br>
                    <span>Amount per Square Meter: {{ formatCurrency(lot.lot_group.amount_per_sqr_meter) }}</span><br>
                    <span>Monthly Payment: {{ formatCurrency(lot.lot_group.monthly_amortizations) }}</span><br>
                    <span>Total Amount: {{ formatCurrency(lot.lot_group.sqr_meter * lot.lot_group.amount_per_sqr_meter) }}</span><br>
                    <span>Status: {{ lot.status }}</span><br>
                    <span>Block: {{ lot.block }}</span><br>
                    <span v-if="lot.user && lot.status === 'Pending'">Applied By: {{ lot.user.personal_info.first_name }} {{ lot.user.personal_info.last_name }}</span>
                    <span v-if="lot.user && lot.status === 'Occupied'">Owned By: {{ lot.user.personal_info.first_name }} {{ lot.user.personal_info.last_name }}</span><br>
                    <span v-if="lot.user && lot.status === 'Occupied'">Remaining Balance: {{ formatCurrency(calculateRemainingBalance(lot)) }}</span><br>
                </button>
            </div>
        </div>
    </div>
</div>

    </Dialog>
  </div>
</template>
