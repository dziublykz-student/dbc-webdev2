<template>
  <MainLayout>
    <section class="bg-gradient-to-br from-black via-gray-900 to-gray-800 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-3xl">
          <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-400 mb-3">
            Inventory
          </p>

          <Heading :level="1" size="3xl" class="mb-4">
            Explore our available cars
          </Heading>

          <p class="text-lg text-gray-300 leading-8 max-w-3xl">
            Browse our current stock, filter by brand, fuel type, or status,
            and open any car to view more details or send an inquiry.
          </p>
        </div>
      </div>
    </section>

    <section class="py-10 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <LoadingState
          v-if="loading"
          message="Loading cars..."
        />

        <ErrorState
          v-else-if="error"
          title="Error Loading Cars"
          :message="error"
          button-text="Try Again"
          @retry="fetchCars"
        />

        <CarInventory
          v-else
          :articles="mappedCars"
          :filters="filters"
          @update:filters="filters = $event"
          @apply-filters="applyFilters"
          @reset-filters="resetFilters"
          @article-click="handleCarClick"
        />
      </div>
    </section>
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { get } from '../../../utils/api.js'
import MainLayout from '../../templates/MainLayout/MainLayout.vue'
import CarInventory from '../../templates/CarInventory/CarInventory.vue'
import LoadingState from '../../organisms/LoadingState/LoadingState.vue'
import ErrorState from '../../organisms/ErrorState/ErrorState.vue'
import Heading from '../../atoms/Heading/Heading.vue'

const cars = ref([])
const loading = ref(true)
const error = ref(null)

const filters = ref({
  brand: '',
  fuelType: '',
  status: '',
})

const mappedCars = computed(() =>
  cars.value.map((car) => ({
    id: car.id,
    title: `${car.brand} ${car.model}`,
    category: car.fuelType,
    content: car.description,
    year: car.year,
    transmission: car.transmission,
    mileage: `${car.mileage.toLocaleString()} km`,
    status: car.status,
    price: `€${Number(car.price).toLocaleString()}`,
    imageUrl: car.imageUrl,
  })),
)

const buildQueryString = () => {
  const params = new URLSearchParams()

  if (filters.value.brand.trim()) params.append('brand', filters.value.brand.trim())
  if (filters.value.fuelType) params.append('fuelType', filters.value.fuelType)
  if (filters.value.status) params.append('status', filters.value.status)

  params.append('page', '1')
  params.append('limit', '12')

  return params.toString()
}

const fetchCars = async () => {
  loading.value = true
  error.value = null

  try {
    const query = buildQueryString()
    const endpoint = query ? `/cars?${query}` : '/cars'
    const response = await get(endpoint)

    if (!response.ok) {
      throw new Error(`Failed to fetch cars: ${response.status} ${response.statusText}`)
    }

    const result = await response.json()
    cars.value = Array.isArray(result) ? result : (result.data?.data ?? result.data ?? [])
  } catch (err) {
    error.value = err.message || 'Failed to load cars.'
    cars.value = []
  } finally {
    loading.value = false
  }
}

const applyFilters = () => fetchCars()

const resetFilters = () => {
  filters.value = { brand: '', fuelType: '', status: '' }
  fetchCars()
}

const handleCarClick = (carId) => {
  window.location.hash = `#/cars/${carId}`
}

onMounted(fetchCars)
</script>