<template>
  <MainLayout>
    <section class="bg-gray-50 py-10">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
          <Heading :level="1" size="3xl" class="mb-2">
            Admin Inquiries
          </Heading>
          <Text as="p" size="lg" color="muted">
            View customer conversations and open a specific inquiry to reply.
          </Text>
        </div>

        <div class="flex flex-wrap gap-4 mb-8">
          <a
            href="#/admin/cars"
            class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-800 hover:bg-gray-50 transition-colors"
          >
            Admin Cars
          </a>

          <a
            href="#/admin/inquiries"
            class="px-4 py-2 bg-gray-900 text-white rounded-xl hover:bg-black transition-colors"
          >
            Admin Inquiries
          </a>
        </div>

        <LoadingState
          v-if="loading"
          message="Loading inquiries..."
        />

        <ErrorState
          v-else-if="error"
          title="Error Loading Inquiries"
          :message="error"
          button-text="Try Again"
          @retry="fetchInquiries"
        />

        <div
          v-else-if="inquiries.length === 0"
          class="bg-white rounded-2xl shadow-md border border-gray-100 text-center py-14 px-6"
        >
          <Heading :level="3" size="lg" class="mb-2">No inquiries found</Heading>
          <Text as="p" size="md" color="muted">
            Customer messages will appear here.
          </Text>
        </div>

        <div v-else class="space-y-4">
          <InquiryListItem
            v-for="inquiry in inquiries"
            :key="inquiry.id"
            :inquiry="inquiry"
          />
        </div>
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
import InquiryListItem from '../../organisms/InquiryListItem/InquiryListItem.vue'

const inquiries = ref([])
const loading = ref(true)
const error = ref(null)

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

onMounted(() => {
  fetchInquiries()
})
</script>