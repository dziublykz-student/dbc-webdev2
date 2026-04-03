<template>
  <div v-if="loading" class="min-h-screen flex items-center justify-center">
    <div class="text-center">
      <div
        class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"
      ></div>
      <p class="text-gray-600">Loading car details...</p>
    </div>
  </div>

  <div
    v-else-if="error"
    class="min-h-screen flex items-center justify-center"
  >
    <div class="text-center max-w-md">
      <div class="text-red-600 text-5xl mb-4">⚠️</div>
      <h2 class="text-2xl font-bold text-gray-900 mb-2">
        Error Loading Car
      </h2>
      <p class="text-gray-600 mb-4">{{ error }}</p>
      <a
        href="#/"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-block"
      >
        Back to Inventory
      </a>
    </div>
  </div>

  <div v-else-if="car" class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <a href="#/" class="text-blue-600 hover:underline mb-6 inline-block">
        ← Back to Inventory
      </a>

      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2">
          <div class="h-80 lg:h-full bg-gray-200">
            <img
              v-if="car.imageUrl"
              :src="car.imageUrl"
              :alt="`${car.brand} ${car.model}`"
              class="w-full h-full object-cover"
            />
            <div
              v-else
              class="w-full h-full flex items-center justify-center text-gray-500"
            >
              No image
            </div>
          </div>

          <div class="p-8">
            <div class="flex items-center justify-between mb-4">
              <span class="text-sm px-3 py-1 rounded-full bg-gray-100 text-gray-700">
                {{ car.fuelType }}
              </span>

              <span
                class="text-sm font-semibold px-3 py-1 rounded-full"
                :class="car.status === 'Available'
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700'"
              >
                {{ car.status }}
              </span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-2">
              {{ car.brand }} {{ car.model }}
            </h1>

            <p class="text-3xl font-bold text-blue-600 mb-6">
              €{{ Number(car.price).toLocaleString() }}
            </p>

            <p class="text-gray-700 mb-6">
              {{ car.description }}
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
              <div class="p-4 bg-gray-50 rounded-lg">
                <strong>Year:</strong> {{ car.year }}
              </div>
              <div class="p-4 bg-gray-50 rounded-lg">
                <strong>Transmission:</strong> {{ car.transmission }}
              </div>
              <div class="p-4 bg-gray-50 rounded-lg">
                <strong>Mileage:</strong> {{ Number(car.mileage).toLocaleString() }} km
              </div>
              <div class="p-4 bg-gray-50 rounded-lg">
                <strong>Fuel Type:</strong> {{ car.fuelType }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { get } from '../../../utils/api.js'

const car = ref(null)
const loading = ref(true)
const error = ref(null)

const getCarIdFromHash = () => {
  const hash = window.location.hash
  const parts = hash.split('/')
  return parts[2]
}

const fetchCar = async () => {
  loading.value = true
  error.value = null

  try {
    const carId = getCarIdFromHash()
    const response = await get(`/cars/${carId}`)

    if (!response.ok) {
      throw new Error(`Failed to fetch car: ${response.status} ${response.statusText}`)
    }

    const result = await response.json()
    car.value = result.data ?? result
  } catch (err) {
    console.error('Error fetching car:', err)
    error.value = err.message || 'Failed to load car details.'
    car.value = null
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchCar()
})
</script>