<template>
  <LoadingState
    v-if="loading"
    message="Loading car details..."
  />

  <ErrorState
    v-else-if="error"
    title="Error Loading Car"
    :message="error"
    button-text="Back to Inventory"
    @retry="goBack"
  />

  <MainLayout v-else-if="car">
    <section class="bg-gray-50 py-10">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <a
          href="#/cars"
          class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium mb-6"
        >
          ← Back to Inventory
        </a>

        <CarDetailHero :car="car" />

        <InquiryForm
          :form="inquiryForm"
          :form-error="formError"
          :form-success="formSuccess"
          @submit="submitInquiry"
          @update:form="inquiryForm = $event"
        />
      </div>
    </section>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { get, post } from '../../../utils/api.js'
import MainLayout from '../../templates/MainLayout/MainLayout.vue'
import LoadingState from '../../organisms/LoadingState/LoadingState.vue'
import ErrorState from '../../organisms/ErrorState/ErrorState.vue'
import CarDetailHero from '../../organisms/CarDetailHero/CarDetailHero.vue'
import InquiryForm from '../../organisms/InquiryForm/InquiryForm.vue'

const car = ref(null)
const loading = ref(true)
const error = ref(null)
const formError = ref('')
const formSuccess = ref('')

const inquiryForm = ref({
  name: '',
  email: '',
  message: '',
})

const getCarIdFromHash = () => {
  const hash = window.location.hash
  const parts = hash.split('/')
  return parts[2]
}

const goBack = () => {
  window.location.hash = '#/cars'
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

    const result = await response.json()

    if (!response.ok) {
      throw new Error(result.error || `Failed to send inquiry: ${response.status} ${response.statusText}`)
    }

    const inquiry = result.data ?? result
    const conversationHash = `#/inquiries/token/${inquiry.publicToken}`

    localStorage.setItem('lastVisitorConversation', conversationHash)
    window.location.hash = conversationHash
  } catch (err) {
    console.error('Error sending inquiry:', err)
    formError.value = err.message || 'Failed to send inquiry.'
  }
}

onMounted(() => {
  fetchCar()
})
</script>