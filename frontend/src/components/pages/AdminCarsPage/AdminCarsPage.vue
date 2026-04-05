<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex items-center justify-between mb-8">
        <div>
          <Heading :level="1" size="3xl">Admin Car Management</Heading>
          <p class="text-gray-600 mt-2">Manage dealership inventory</p>
        </div>

        <button
          v-if="isAdmin"
          type="button"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          @click="
            showCreateForm = !showCreateForm;
            formError = '';
            formSuccess = '';
            if (!showCreateForm) resetForm();
          "
        >
          {{ showCreateForm ? 'Close Form' : 'Add New Car' }}
        </button>
      </div>

      <div
        v-if="!isAdmin"
        class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl p-4 mb-8"
      >
        You are logged in as <strong>{{ currentUser?.role || 'employee' }}</strong>.
        You can view the admin inventory, but only admins can create, edit, or delete cars.
      </div>

      <AdminCarForm
        v-if="showCreateForm && isAdmin"
        :form="form"
        :is-editing="isEditing"
        :form-error="formError"
        :form-success="formSuccess"
        @save="saveCar"
        @update:form="form = $event"
      />

      <p v-if="globalSuccess" class="mb-4 text-green-600 font-medium">
        {{ globalSuccess }}
      </p>

      <p v-if="globalError" class="mb-4 text-red-600 font-medium">
        {{ globalError }}
      </p>

      <div v-if="loading" class="text-center py-12 text-gray-600">
        Loading admin cars...
      </div>

      <div v-else-if="error" class="text-center py-12 text-red-600">
        {{ error }}
      </div>

      <AdminCarsTable
        v-else
        :cars="cars"
        :is-admin="isAdmin"
        @edit="startEdit"
        @delete="deleteCar"
      />

      <div class="mt-6 flex gap-4">
        <a href="#/" class="text-blue-600 hover:underline">← Back to Inventory</a>
        <button
          type="button"
          class="text-red-600 hover:underline"
          @click="logout"
        >
          Logout
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { get } from '../../../utils/api.js'
import Heading from '../../atoms/Heading/Heading.vue'
import AdminCarForm from '../../organisms/AdminCarForm/AdminCarForm.vue'
import AdminCarsTable from '../../organisms/AdminCarsTable/AdminCarsTable.vue'

const cars = ref([])
const loading = ref(true)
const error = ref(null)
const showCreateForm = ref(false)
const formError = ref('')
const formSuccess = ref('')
const globalError = ref('')
const globalSuccess = ref('')
const editingCarId = ref(null)
const isEditing = ref(false)

const currentUser = ref(
  JSON.parse(localStorage.getItem('user') || 'null')
)

const isAdmin = computed(() => currentUser.value?.role === 'admin')

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

const getAuthHeaders = () => {
  const token = localStorage.getItem('token')

  return {
    'Content-Type': 'application/json',
    Authorization: `Bearer ${token}`,
  }
}

const fetchCars = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await get('/cars')

    if (!response.ok) {
      throw new Error(`Failed to fetch cars: ${response.status} ${response.statusText}`)
    }

    const result = await response.json()
    cars.value = Array.isArray(result) ? result : (result.data ?? [])
  } catch (err) {
    console.error('Error fetching admin cars:', err)
    error.value = err.message || 'Failed to load admin cars.'
    cars.value = []
  } finally {
    loading.value = false
  }
}

const resetForm = () => {
  form.value = {
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
  }

  editingCarId.value = null
  isEditing.value = false
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

const startEdit = (car) => {
  if (!isAdmin.value) return

  formError.value = ''
  formSuccess.value = ''
  globalError.value = ''
  globalSuccess.value = ''
  showCreateForm.value = true
  isEditing.value = true
  editingCarId.value = car.id

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
}

const saveCar = async () => {
  if (!isAdmin.value) return

  formError.value = ''
  formSuccess.value = ''
  globalError.value = ''
  globalSuccess.value = ''

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

    if (isEditing.value && editingCarId.value !== null) {
      response = await fetch(`http://localhost/cars/${editingCarId.value}`, {
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

    globalSuccess.value = isEditing.value
      ? 'Car updated successfully.'
      : 'Car created successfully.'

    showCreateForm.value = false
    resetForm()
    await fetchCars()
  } catch (err) {
    console.error('Error saving car:', err)
    formError.value = err.message || 'Failed to save car.'
  }
}

const deleteCar = async (carId) => {
  if (!isAdmin.value) return

  formError.value = ''
  formSuccess.value = ''
  globalError.value = ''
  globalSuccess.value = ''

  const confirmed = window.confirm('Are you sure you want to delete this car?')
  if (!confirmed) {
    return
  }

  try {
    const response = await fetch(`http://localhost/cars/${carId}`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
    })

    if (!response.ok) {
      if (response.status === 401) {
        throw new Error('Unauthorized. Please log in again.')
      }

      if (response.status === 403) {
        throw new Error('Forbidden. Admin role required.')
      }

      throw new Error(`Failed to delete car: ${response.status} ${response.statusText}`)
    }

    globalSuccess.value = 'Car deleted successfully.'
    await fetchCars()
  } catch (err) {
    console.error('Error deleting car:', err)
    globalError.value = err.message || 'Failed to delete car.'
  }
}

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  window.location.hash = '#/login'
}

onMounted(() => {
  fetchCars()
})
</script>