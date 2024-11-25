<script setup>
import { ref, onMounted, nextTick } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import axios from 'axios'
import Show from '../show.vue'

const defaultLatLng = [7.3151, 125.6190]
const latLng = ref(defaultLatLng)
const map = ref(null)
const showModal = ref(false)
const modalContent = ref('')
const mapContainer = ref(null)
const modalBtn = ref(null)
const property = ref(null)
const props = defineProps({
  address: String,
  map: String,
  property: Object
})

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



onMounted(() => {
  nextTick(() => {
    property.value = props.property
    map.value = L.map(mapContainer.value).setView(latLng.value, 20)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map.value)

    const marker = L.marker(latLng.value).addTo(map.value)
    marker.bindPopup("Marker Location").openPopup() 

    if (props.address) {
      geocodeAddress(props.address).then(() => {
        map.value.setView(latLng.value, 20)
        marker.setLatLng(latLng.value)
        marker.bindPopup('Purok ' + props.address).openPopup()
      })
    }

    console.log(property)
  })
})
</script>

<template>
  <div>
    <div id="map" ref="mapContainer" style="height: 500px;"></div>
  </div>
</template>
