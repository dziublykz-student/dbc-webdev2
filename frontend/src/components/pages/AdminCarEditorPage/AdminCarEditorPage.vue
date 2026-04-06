<template>
  <MainLayout>
    <section class="bg-gray-50 py-10">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <a
          href="#/admin/cars"
          class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium mb-6"
        >
          ← Back to Admin Cars
        </a>

        <div class="mb-8">
          <Heading :level="1" size="3xl" class="mb-2">
            {{ isEditing ? 'Edit Car' : 'Add New Car' }}
          </Heading>

          <p class="text-gray-600">
            {{ isEditing
              ? 'Update the selected vehicle in the dealership inventory.'
              : 'Create a new vehicle entry for the dealership inventory.' }}
          </p>
        </div>

        <LoadingState
          v-if="loading"
          :message="isEditing ? 'Loading car...' : 'Preparing form...'"
        />

        <ErrorState
          v-else-if="error"
          title="Error"
          :message="error"
          button-text="Back to Admin Cars"
          @retry="goBack"
        />

        <AdminCarForm
          v-else
          :form="form"
          :is-editing="isEditing"
          :form-error="formError"
          :form-success="formSuccess"
          @save="saveCar"
          @update:form="form = $event"
        />
      </div>
    </section>
  </MainLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { get } from '../../../utils/api.js'
import MainLayout from '../../templates/MainLayout/MainLayout.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import LoadingState from '../../organisms/LoadingState/LoadingState.vue'
import ErrorState from '../../organisms/ErrorState/ErrorState.vue'
import AdminCarForm from '../../organisms/AdminCarForm/AdminCarForm.vue'

const loading = ref(true)
const error = ref(null)
const formError = ref('')
const formSuccess = ref('')

const form = ref({
  brand: '',
  model: '',
  year: '',
  price: '',
  mileage: '',
  fuelType: '',
  transmission: '',
  status: 'Available',
  imageUrl: '',
  description: '',
})

const isEditing = computed(() => window.location.hash.startsWith('#/admin/cars/edit/'))

const getCarIdFromHash = () => {
  const parts = window.location.hash.split('/')
  return parts[4] || null
}

const goBack = () => {
  window.location.hash = '#/admin/cars'
}

const getAuthHeaders = () => {
  const token = localStorage.getItem('token')

  return {
    'Content-Type': 'application/json',
    Authorization: `Bearer ${token}`,
  }
}

const validateForm = () => {
  const requiredFields = [
    'brand',
    'model',
    'year',
    'price',
    'mileage',
    'fuelType',
    'transmission',
    'status',
    'imageUrl',
    'description',
  ]

  for (const field of requiredFields) {
    if (!form.value[field] || String(form.value[field]).trim() === '') {
      return false
    }
  }

  return true
}

const fetchCar = async () => {
  if (!isEditing.value) {
    loading.value = false
    return
  }

  loading.value = true
  error.value = null

  try {
    const carId = getCarIdFromHash()
    const response = await get(`/cars/${carId}`)

    if (!response.ok) {
      throw new Error(`Failed to fetch car: ${response.status} ${response.statusText}`)
    }

    const result = await response.json()
    const car = result.data ?? result

    form.value = {
      brand: car.brand,
      model: car.model,
      year: car.year,
      price: car.price,
      mileage: car.mileage,
      fuelType: car.fuelType,
      transmission: car.transmission,
      status: car.status,
      imageUrl: car.imageUrl,
      description: car.description,
    }
  } catch (err) {
    console.error('Error fetching car:', err)
    error.value = err.message || 'Failed to load car.'
  } finally {
    loading.value = false
  }
}

const saveCar = async () => {
  formError.value = ''
  formSuccess.value = ''

  if (!validateForm()) {
    formError.value = 'Please fill in all fields before saving the car.'
    return
  }

  try {
    let response

    const payload = {
      brand: form.value.brand,
      model: form.value.model,
      year: Number(form.value.year),
      price: Number(form.value.price),
      mileage: Number(form.value.mileage),
      fuelType: form.value.fuelType,
      transmission: form.value.transmission,
      status: form.value.status,
      imageUrl: form.value.imageUrl,
      description: form.value.description,
    }

    if (isEditing.value) {
      const carId = getCarIdFromHash()
      response = await fetch(`http://localhost/cars/${carId}`, {
        method: 'PUT',
        headers: getAuthHeaders(),
        body: JSON.stringify(payload),
      })
    } else {
      response = await fetch('http://localhost/cars', {
        method: 'POST',
        headers: getAuthHeaders(),
        body: JSON.stringify(payload),
      })
    }

    if (!response.ok) {
      if (response.status === 401) {
        throw new Error('Unauthorized. Please log in again.')
      }

      if (response.status === 403) {
        throw new Error('Forbidden. Admin role required.')
      }

      throw new Error(`Failed to save car: ${response.status} ${response.statusText}`)
    }

    formSuccess.value = isEditing.value
      ? 'Car updated successfully.'
      : 'Car created successfully.'

    setTimeout(() => {
      window.location.hash = '#/admin/cars'
    }, 700)
  } catch (err) {
    console.error('Error saving car:', err)
    formError.value = err.message || 'Failed to save car.'
  }
}

onMounted(() => {
  fetchCar()
})
</script>