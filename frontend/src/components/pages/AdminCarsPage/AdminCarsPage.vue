<template>
  <MainLayout>
    <section class="bg-gray-50 py-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-8">
          <div>
            <Heading :level="1" size="3xl" class="mb-2">
              Admin Car Management
            </Heading>

            <p class="text-gray-600">
              Manage dealership inventory and open a separate page to create or edit cars.
            </p>
          </div>

          <a
            v-if="isAdmin"
            href="#/admin/cars/new"
            class="px-5 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition-colors font-medium inline-flex items-center justify-center"
          >
            Add New Car
          </a>
        </div>

        <div class="flex flex-wrap gap-4 mb-8">
          <a
            href="#/admin/cars"
            class="px-4 py-2 bg-gray-900 text-white rounded-xl hover:bg-black transition-colors"
          >
            Admin Cars
          </a>

          <a
            href="#/admin/inquiries"
            class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-800 hover:bg-gray-50 transition-colors"
          >
            Admin Inquiries
          </a>

          <a
            href="#/cars"
            class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-800 hover:bg-gray-50 transition-colors"
          >
            View Public Inventory
          </a>
        </div>

        <div
          v-if="!isAdmin"
          class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl p-4 mb-8"
        >
          You are logged in as <strong>{{ currentUser?.role || 'employee' }}</strong>.
          You can view the admin inventory, but only admins can create, edit, or delete cars.
        </div>

        <p v-if="globalSuccess" class="mb-4 text-green-600 font-medium">
          {{ globalSuccess }}
        </p>

        <p v-if="globalError" class="mb-4 text-red-600 font-medium">
          {{ globalError }}
        </p>

        <LoadingState
          v-if="loading"
          message="Loading admin cars..."
        />

        <ErrorState
          v-else-if="error"
          title="Error Loading Cars"
          :message="error"
          button-text="Try Again"
          @retry="fetchCars"
        />

        <AdminCarsTable
          v-else
          :cars="cars"
          :is-admin="isAdmin"
          @edit="startEdit"
          @delete="deleteCar"
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
import AdminCarsTable from '../../organisms/AdminCarsTable/AdminCarsTable.vue'

const cars = ref([])
const loading = ref(true)
const error = ref(null)
const globalError = ref('')
const globalSuccess = ref('')

const currentUser = ref(
  JSON.parse(localStorage.getItem('user') || 'null')
)

const isAdmin = computed(() => currentUser.value?.role === 'admin')

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

const startEdit = (car) => {
  if (!isAdmin.value) return
  window.location.hash = `#/admin/cars/edit/${car.id}`
}

const deleteCar = async (carId) => {
  if (!isAdmin.value) return

  globalError.value = ''
  globalSuccess.value = ''

  const confirmed = window.confirm('Are you sure you want to delete this car?')
  if (!confirmed) return

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

onMounted(() => {
  fetchCars()
})
</script>