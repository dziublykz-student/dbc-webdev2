<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex items-center justify-between mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Admin Inquiries</h1>
          <p class="text-gray-600 mt-2">View customer interest messages for cars</p>
        </div>
      </div>

      <p v-if="globalError" class="mb-4 text-red-600 font-medium">
        {{ globalError }}
      </p>

      <div v-if="loading" class="text-center py-12 text-gray-600">
        Loading inquiries...
      </div>

      <div v-else-if="error" class="text-center py-12 text-red-600">
        {{ error }}
      </div>

      <div v-else-if="inquiries.length === 0" class="bg-white rounded-xl shadow-md p-8 text-center text-gray-600">
        No inquiries found.
      </div>

      <div v-else class="space-y-4">
        <div
          v-for="inquiry in inquiries"
          :key="inquiry.id"
          class="bg-white rounded-xl shadow-md p-6"
        >
          <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-4">
            <div>
              <h2 class="text-xl font-semibold text-gray-900">
                Inquiry #{{ inquiry.id }}
              </h2>
              <p class="text-sm text-gray-600">
                Car ID: {{ inquiry.carId }}
              </p>

              <p class="text-sm text-gray-600">
                Car Brand: {{ inquiry.carBrand || 'Unknown' }}
              </p>
            </div>

            <p class="text-sm text-gray-500">
              {{ formatDate(inquiry.createdAt) }}
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 text-sm text-gray-700">
            <div>
              <strong>Name:</strong> {{ inquiry.name }}
            </div>
            <div>
              <strong>Email:</strong> {{ inquiry.email }}
            </div>
          </div>

          <div class="bg-gray-50 rounded-lg p-4 text-gray-800">
            {{ inquiry.message }}
          </div>
        </div>
      </div>

      <div class="mt-6 flex gap-4">
        <a href="#/admin/cars" class="text-blue-600 hover:underline">← Back to Admin Cars</a>
        <a href="#/" class="text-blue-600 hover:underline">Back to Inventory</a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const inquiries = ref([])
const loading = ref(true)
const error = ref(null)
const globalError = ref('')

const getAuthHeaders = () => {
  const token = localStorage.getItem('token')

  return {
    'Content-Type': 'application/json',
    Authorization: `Bearer ${token}`,
  }
}

const fetchInquiries = async () => {
  loading.value = true
  error.value = null
  globalError.value = ''

  try {
    const response = await fetch('http://localhost/inquiries', {
      method: 'GET',
      headers: getAuthHeaders(),
    })

    const text = await response.text()
    let result = null

    try {
      result = JSON.parse(text)
    } catch {
      throw new Error(`Server did not return valid JSON. Response was: ${text.substring(0, 100)}`)
    }

    if (!response.ok) {
      throw new Error(result.error || `Failed to fetch inquiries: ${response.status}`)
    }

    inquiries.value = Array.isArray(result) ? result : (result.data ?? [])
  } catch (err) {
    console.error('Error fetching inquiries:', err)
    error.value = err.message || 'Failed to load inquiries.'
    inquiries.value = []
  } finally {
    loading.value = false
  }
}

const formatDate = (value) => {
  try {
    return new Date(value).toLocaleString()
  } catch {
    return value
  }
}

onMounted(() => {
  fetchInquiries()
})
</script>