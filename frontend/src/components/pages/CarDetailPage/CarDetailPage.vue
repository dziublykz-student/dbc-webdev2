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

      <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
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

      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
          Interested in this car?
        </h2>
        <p class="text-gray-600 mb-6">
          Send a message and the dealership can follow up with you.
        </p>

        <p v-if="formError" class="mb-4 text-red-600 font-medium">
          {{ formError }}
        </p>

        <p v-if="formSuccess" class="mb-4 text-green-600 font-medium">
          {{ formSuccess }}
        </p>

        <form @submit.prevent="submitInquiry" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <input
            v-model="inquiryForm.name"
            type="text"
            placeholder="Your name"
            class="border rounded-lg px-4 py-2"
          />

          <input
            v-model="inquiryForm.email"
            type="email"
            placeholder="Your email"
            class="border rounded-lg px-4 py-2"
          />

          <textarea
            v-model="inquiryForm.message"
            placeholder="Your message"
            class="border rounded-lg px-4 py-2 md:col-span-2"
            rows="5"
          ></textarea>

          <button
            type="submit"
            class="md:col-span-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            Send Inquiry
          </button>
        </form>
      </div>

      <div
        v-if="lastInquiryId"
        class="bg-white rounded-xl shadow-md p-6"
      >
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
          Continue this conversation
        </h2>
        <p class="text-gray-600 mb-4">
          You already sent an inquiry for this car. You can send a follow-up message here.
        </p>

        <p v-if="followUpError" class="mb-4 text-red-600 font-medium">
          {{ followUpError }}
        </p>

        <p v-if="followUpSuccess" class="mb-4 text-green-600 font-medium">
          {{ followUpSuccess }}
        </p>

        <form @submit.prevent="submitFollowUp" class="space-y-4">
          <input
            v-model="followUpEmail"
            type="email"
            placeholder="Use the same email as before"
            class="w-full border rounded-lg px-4 py-2"
          />

          <textarea
            v-model="followUpMessage"
            placeholder="Write your follow-up message"
            class="w-full border rounded-lg px-4 py-2"
            rows="5"
          ></textarea>

          <button
            type="submit"
            class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-black transition-colors"
          >
            Send Follow-up
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { get, post } from '../../../utils/api.js'

const car = ref(null)
const loading = ref(true)
const error = ref(null)

const formError = ref('')
const formSuccess = ref('')

const followUpError = ref('')
const followUpSuccess = ref('')

const inquiryForm = ref({
  name: '',
  email: '',
  message: '',
})

const lastInquiryId = ref(null)
const followUpEmail = ref('')
const followUpMessage = ref('')

const getCarIdFromHash = () => {
  const hash = window.location.hash
  const parts = hash.split('/')
  return parts[2]
}

const getStorageKey = () => {
  return `carInquiry_${getCarIdFromHash()}`
}

const loadStoredInquiry = () => {
  const raw = localStorage.getItem(getStorageKey())
  if (!raw) return

  try {
    const data = JSON.parse(raw)
    lastInquiryId.value = data.inquiryId ?? null
    followUpEmail.value = data.email ?? ''
  } catch {
    lastInquiryId.value = null
  }
}

const saveStoredInquiry = (inquiryId, email) => {
  localStorage.setItem(
    getStorageKey(),
    JSON.stringify({ inquiryId, email })
  )
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

const validateInquiryForm = () => {
  if (!inquiryForm.value.name.trim()) return false
  if (!inquiryForm.value.email.trim()) return false
  if (!inquiryForm.value.message.trim()) return false
  return true
}

const resetInquiryForm = () => {
  inquiryForm.value = {
    name: '',
    email: '',
    message: '',
  }
}

const submitInquiry = async () => {
  formError.value = ''
  formSuccess.value = ''

  if (!validateInquiryForm()) {
    formError.value = 'Please fill in all fields before sending your inquiry.'
    return
  }

  try {
    const response = await post('/inquiries', {
      carId: car.value.id,
      name: inquiryForm.value.name,
      email: inquiryForm.value.email,
      message: inquiryForm.value.message,
    })

    if (!response.ok) {
      throw new Error(`Failed to send inquiry: ${response.status} ${response.statusText}`)
    }

    const result = await response.json()
    const inquiry = result.data ?? result

    lastInquiryId.value = inquiry.id
    followUpEmail.value = inquiryForm.value.email
    saveStoredInquiry(inquiry.id, inquiryForm.value.email)

    formSuccess.value = 'Your inquiry has been sent successfully.'
    resetInquiryForm()
  } catch (err) {
    console.error('Error sending inquiry:', err)
    formError.value = err.message || 'Failed to send inquiry.'
  }
}

const submitFollowUp = async () => {
  followUpError.value = ''
  followUpSuccess.value = ''

  if (!lastInquiryId.value) {
    followUpError.value = 'No inquiry found to continue.'
    return
  }

  if (!followUpEmail.value.trim() || !followUpMessage.value.trim()) {
    followUpError.value = 'Please fill in both email and follow-up message.'
    return
  }

  try {
    const response = await post(`/inquiries/${lastInquiryId.value}/messages`, {
      email: followUpEmail.value,
      message: followUpMessage.value,
    })

    if (!response.ok) {
      throw new Error(`Failed to send follow-up: ${response.status} ${response.statusText}`)
    }

    followUpSuccess.value = 'Your follow-up has been sent successfully.'
    followUpMessage.value = ''
  } catch (err) {
    console.error('Error sending follow-up:', err)
    followUpError.value = err.message || 'Failed to send follow-up.'
  }
}

onMounted(() => {
  fetchCar()
  loadStoredInquiry()
})
</script>