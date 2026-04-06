<template>
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
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import CarInventory from '../../templates/CarInventory/CarInventory.vue'
import LoadingState from '../../organisms/LoadingState/LoadingState.vue'
import ErrorState from '../../organisms/ErrorState/ErrorState.vue'
import { get } from '../../../utils/api.js'

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

  if (filters.value.brand.trim()) {
    params.append('brand', filters.value.brand.trim())
  }

  if (filters.value.fuelType) {
    params.append('fuelType', filters.value.fuelType)
  }

  if (filters.value.status) {
    params.append('status', filters.value.status)
  }

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
    cars.value = Array.isArray(result)
      ? result
      : (result.data?.data ?? result.data ?? [])
  } catch (err) {
    console.error('Error fetching cars:', err)
    error.value = err.message || 'Failed to load cars. Please try again later.'
    cars.value = []
  } finally {
    loading.value = false
  }
}

const applyFilters = () => {
  fetchCars()
}

const resetFilters = () => {
  filters.value = {
    brand: '',
    fuelType: '',
    status: '',
  }
  fetchCars()
}

const handleCarClick = (carId) => {
  window.location.hash = `#/cars/${carId}`
}

onMounted(() => {
  fetchCars()
})
</script>