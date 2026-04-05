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

      <p v-if="globalSuccess" class="mb-4 text-green-600 font-medium">
        {{ globalSuccess }}
      </p>

      <div v-if="loading" class="text-center py-12 text-gray-600">
        Loading inquiries...
      </div>

      <div v-else-if="error" class="text-center py-12 text-red-600">
        {{ error }}
      </div>

      <div
        v-else-if="inquiries.length === 0"
        class="bg-white rounded-xl shadow-md p-8 text-center text-gray-600"
      >
        No inquiries found.
      </div>

      <div v-else class="space-y-6">
        <div
          v-for="inquiry in inquiries"
          :key="inquiry.id"
          class="bg-white rounded-xl shadow-md p-6"
        >
          <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-6">
            <div>
              <h2 class="text-xl font-semibold text-gray-900">
                Inquiry #{{ inquiry.id }}
              </h2>
              <p class="text-sm text-gray-600">
                Car ID: {{ inquiry.carId }}
              </p>
              <p class="text-sm text-gray-600">
                {{ inquiry.name }} • {{ inquiry.email }}
              </p>
            </div>

            <div class="text-right">
              <p class="text-sm text-gray-500 mb-1">
                {{ formatDate(inquiry.createdAt) }}
              </p>
              <span
                class="text-xs font-semibold px-2 py-1 rounded-full"
                :class="inquiry.status === 'handled'
                  ? 'bg-green-100 text-green-700'
                  : 'bg-yellow-100 text-yellow-700'"
              >
                {{ inquiry.status }}
              </span>
            </div>
          </div>

          <div class="space-y-3 mb-6">
            <div
              v-for="message in inquiry.messages"
              :key="message.id"
              class="flex"
              :class="message.senderType === 'admin' ? 'justify-end' : 'justify-start'"
            >
              <div
                class="max-w-[75%] rounded-2xl px-4 py-3 shadow-sm"
                :class="message.senderType === 'admin'
                  ? 'bg-blue-600 text-white'
                  : 'bg-gray-100 text-gray-900'"
              >
                <div class="text-xs opacity-80 mb-1 font-semibold">
                  {{ message.senderType === 'admin' ? 'Admin' : inquiry.name }}
                </div>
                <div class="whitespace-pre-wrap break-words">
                  {{ message.message }}
                </div>
                <div
                  class="text-[11px] mt-2"
                  :class="message.senderType === 'admin' ? 'text-blue-100' : 'text-gray-500'"
                >
                  {{ formatDate(message.createdAt) }}
                </div>
              </div>
            </div>
          </div>

          <div class="border-t pt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Reply as admin
            </label>
            <textarea
              v-model="replyForms[inquiry.id].adminReply"
              class="w-full border rounded-lg px-4 py-2 mb-3"
              rows="4"
              placeholder="Write a reply..."
            ></textarea>

            <div class="flex flex-col md:flex-row gap-3 md:items-center">
              <select
                v-model="replyForms[inquiry.id].status"
                class="border rounded-lg px-4 py-2"
              >
                <option value="new">new</option>
                <option value="handled">handled</option>
              </select>

              <button
                type="button"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                @click="saveReply(inquiry.id)"
              >
                Send Reply
              </button>
            </div>
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
const globalSuccess = ref('')
const replyForms = ref({})

const getAuthHeaders = () => {
  const token = localStorage.getItem('token')

  return {
    'Content-Type': 'application/json',
    Authorization: `Bearer ${token}`,
  }
}

const initializeReplyForms = () => {
  const forms = {}
  for (const inquiry of inquiries.value) {
    forms[inquiry.id] = {
      adminReply: '',
      status: inquiry.status ?? 'new',
    }
  }
  replyForms.value = forms
}

const fetchInquiries = async () => {
  loading.value = true
  error.value = null
  globalError.value = ''
  globalSuccess.value = ''

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
    initializeReplyForms()
  } catch (err) {
    console.error('Error fetching inquiries:', err)
    error.value = err.message || 'Failed to load inquiries.'
    inquiries.value = []
  } finally {
    loading.value = false
  }
}

const saveReply = async (inquiryId) => {
  globalError.value = ''
  globalSuccess.value = ''

  const replyText = replyForms.value[inquiryId].adminReply?.trim() ?? ''
  const status = replyForms.value[inquiryId].status

  if (!replyText && !status) {
    globalError.value = 'Please enter a reply or choose a status.'
    return
  }

  try {
    const payload = {
      adminReply: replyText,
      status,
    }

    const response = await fetch(`http://localhost/inquiries/${inquiryId}`, {
      method: 'PUT',
      headers: getAuthHeaders(),
      body: JSON.stringify(payload),
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

    globalSuccess.value = 'Inquiry updated successfully.'
    await fetchInquiries()
  } catch (err) {
    console.error('Error updating inquiry:', err)
    globalError.value = err.message || 'Failed to update inquiry.'
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