<template>
  <MainLayout>
    <section class="bg-gray-50 py-10">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-4 mb-6">
            <a
                href="#/admin/cars"
                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-800 hover:bg-gray-50 transition-colors"
            >
                Admin Cars
            </a>

            <a
                href="#/admin/inquiries"
                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-800 hover:bg-gray-50 transition-colors"
            >
                All Inquiries
            </a>
        </div>

        <LoadingState
          v-if="loading"
          message="Loading inquiry..."
        />

        <ErrorState
          v-else-if="error"
          title="Error Loading Inquiry"
          :message="error"
          button-text="Back to Inquiries"
          @retry="goBack"
        />

        <template v-else-if="inquiry">
          <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 mb-8">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
              <div>
                <Heading :level="1" size="3xl" class="mb-2">
                  Inquiry #{{ inquiry.id }}
                </Heading>

                <Text as="p" size="sm" color="muted" class="mb-1">
                  {{ inquiry.name }} • {{ inquiry.email }}
                </Text>

                <Text as="p" size="sm" color="muted">
                  Car ID: {{ inquiry.carId }}
                </Text>
              </div>

              <div class="text-left md:text-right">
                <Text as="p" size="sm" color="muted" class="mb-2">
                  {{ formatDate(inquiry.createdAt) }}
                </Text>

                <span
                  class="text-xs font-semibold px-3 py-1 rounded-full"
                  :class="inquiry.status === 'handled'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-yellow-100 text-yellow-700'"
                >
                  {{ inquiry.status }}
                </span>
              </div>
            </div>

            <InquiryThread
              :messages="inquiry.messages"
              :customer-name="inquiry.name"
            />
          </div>

          <AdminReplyForm
            :form="replyForm"
            :error="replyError"
            :success="replySuccess"
            @submit="saveReply"
            @update:form="replyForm = $event"
          />
        </template>
      </div>
    </section>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import MainLayout from '../../templates/MainLayout/MainLayout.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'
import LoadingState from '../../organisms/LoadingState/LoadingState.vue'
import ErrorState from '../../organisms/ErrorState/ErrorState.vue'
import InquiryThread from '../../organisms/InquiryThread/InquiryThread.vue'
import AdminReplyForm from '../../organisms/AdminReplyForm/AdminReplyForm.vue'

const inquiry = ref(null)
const loading = ref(true)
const error = ref(null)
const replyError = ref('')
const replySuccess = ref('')
const replyForm = ref({
  adminReply: '',
  status: 'new',
})

const getInquiryIdFromHash = () => {
  const parts = window.location.hash.split('/')
  return parts[3]
}

const getAuthHeaders = () => {
  const token = localStorage.getItem('token')

  return {
    'Content-Type': 'application/json',
    Authorization: `Bearer ${token}`,
  }
}

const goBack = () => {
  window.location.hash = '#/admin/inquiries'
}

const fetchInquiry = async () => {
  loading.value = true
  error.value = null

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

    const inquiries = Array.isArray(result) ? result : (result.data ?? [])
    const inquiryId = Number(getInquiryIdFromHash())

    inquiry.value = inquiries.find((item) => item.id === inquiryId) || null

    if (!inquiry.value) {
      throw new Error('Inquiry not found.')
    }

    replyForm.value = {
      adminReply: '',
      status: inquiry.value.status ?? 'new',
    }
  } catch (err) {
    console.error('Error fetching inquiry:', err)
    error.value = err.message || 'Failed to load inquiry.'
    inquiry.value = null
  } finally {
    loading.value = false
  }
}

const saveReply = async () => {
  replyError.value = ''
  replySuccess.value = ''

  if (!inquiry.value) {
    replyError.value = 'Inquiry not found.'
    return
  }

  try {
    const response = await fetch(`http://localhost/inquiries/${inquiry.value.id}`, {
      method: 'PUT',
      headers: getAuthHeaders(),
      body: JSON.stringify({
        adminReply: replyForm.value.adminReply,
        status: replyForm.value.status,
      }),
    })

    const text = await response.text()
    let result = null

    try {
      result = JSON.parse(text)
    } catch {
      throw new Error(`Server did not return valid JSON. Response was: ${text.substring(0, 100)}`)
    }

    if (!response.ok) {
      throw new Error(result.error || `Failed to update inquiry: ${response.status}`)
    }

    replySuccess.value = 'Reply sent successfully.'
    await fetchInquiry()
  } catch (err) {
    console.error('Error updating inquiry:', err)
    replyError.value = err.message || 'Failed to update inquiry.'
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
  fetchInquiry()
})
</script>