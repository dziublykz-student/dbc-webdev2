<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <a href="#/" class="text-blue-600 hover:underline mb-6 inline-block">
        ← Back to Inventory
      </a>

      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
          Your inquiry conversation
        </h1>
        <p class="text-gray-600">
          Continue your conversation with the dealership here.
        </p>
      </div>

      <div v-if="loading" class="text-center py-12 text-gray-600">
        Loading conversation...
      </div>

      <div v-else-if="error" class="bg-white rounded-xl shadow-md p-6 text-red-600">
        {{ error }}
      </div>

      <template v-else-if="conversation">
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
          <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-6">
            <div>
              <h2 class="text-xl font-semibold text-gray-900">
                Inquiry #{{ conversation.id }}
              </h2>
              <p class="text-sm text-gray-600 mt-2">
                Save this inquiry ID: <strong>{{ conversation.id }}</strong>
              </p>
              <p class="text-sm text-gray-600">
                {{ conversation.name }} • {{ conversation.email }}
              </p>
            </div>

            <div class="text-right">
              <p class="text-sm text-gray-500 mb-1">
                {{ formatDate(conversation.createdAt) }}
              </p>
              <span
                class="text-xs font-semibold px-2 py-1 rounded-full"
                :class="conversation.status === 'handled'
                  ? 'bg-green-100 text-green-700'
                  : 'bg-yellow-100 text-yellow-700'"
              >
                {{ conversation.status }}
              </span>
            </div>
          </div>

          <div class="space-y-3">
            <div
              v-for="message in conversation.messages"
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
                  {{ message.senderType === 'admin' ? 'Dealership' : 'You' }}
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
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">
            Send a follow-up
          </h2>

          <p v-if="followUpError" class="mb-4 text-red-600 font-medium">
            {{ followUpError }}
          </p>

          <p v-if="followUpSuccess" class="mb-4 text-green-600 font-medium">
            {{ followUpSuccess }}
          </p>

          <form @submit.prevent="submitFollowUp" class="space-y-4">
            <input
              v-model="email"
              type="email"
              placeholder="Your email"
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

        <div class="bg-white rounded-xl shadow-md p-6">
          <a
            v-if="lastVisitorConversation"
            :href="lastVisitorConversation"
            class="text-blue-600 hover:underline"
          >
            Refresh / reopen this conversation later
          </a>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { get, post } from '../../../utils/api.js'

const conversation = ref(null)
const loading = ref(true)
const error = ref(null)
const email = ref('')
const followUpMessage = ref('')
const followUpError = ref('')
const followUpSuccess = ref('')
const lastVisitorConversation = ref('')

const getInquiryIdFromHash = () => {
  const hashWithoutQuery = window.location.hash.split('?')[0]
  const parts = hashWithoutQuery.split('/')
  return parts[2]
}

const getEmailFromHash = () => {
  const hash = window.location.hash
  const queryString = hash.includes('?') ? hash.split('?')[1] : ''
  const params = new URLSearchParams(queryString)
  return params.get('email') || ''
}

const fetchConversation = async () => {
  loading.value = true
  error.value = null

  const inquiryId = getInquiryIdFromHash()
  const currentEmail = email.value.trim()

  if (!inquiryId || !currentEmail) {
    error.value = 'Missing inquiry id or email.'
    loading.value = false
    return
  }

  try {
    const encodedEmail = encodeURIComponent(currentEmail)
    const response = await get(`/inquiries/${inquiryId}/view?email=${encodedEmail}`)
    const result = await response.json()

    if (!response.ok) {
      throw new Error(result.error || `Failed to load conversation: ${response.status} ${response.statusText}`)
    }

    conversation.value = result.data ?? result
  } catch (err) {
    console.error('Error loading conversation:', err)
    error.value = err.message || 'Failed to load conversation.'
    conversation.value = null
  } finally {
    loading.value = false
  }
}

const submitFollowUp = async () => {
  followUpError.value = ''
  followUpSuccess.value = ''

  const inquiryId = getInquiryIdFromHash()

  if (!inquiryId) {
    followUpError.value = 'Missing inquiry id.'
    return
  }

  if (!email.value.trim() || !followUpMessage.value.trim()) {
    followUpError.value = 'Please fill in both email and follow-up message.'
    return
  }

  try {
    const response = await post(`/inquiries/${inquiryId}/messages`, {
      email: email.value,
      message: followUpMessage.value,
    })

    const result = await response.json()

    if (!response.ok) {
      throw new Error(result.error || `Failed to send follow-up: ${response.status} ${response.statusText}`)
    }

    followUpSuccess.value = 'Your follow-up has been sent successfully.'
    followUpMessage.value = ''
    await fetchConversation()
  } catch (err) {
    console.error('Error sending follow-up:', err)
    followUpError.value = err.message || 'Failed to send follow-up.'
  }
}

const formatDate = (value) => {
  try {
    return new Date(value).toLocaleString()
  } catch {
    return value
  }
}

onMounted(async () => {
  email.value = decodeURIComponent(getEmailFromHash())
  lastVisitorConversation.value = window.location.hash
  localStorage.setItem('lastVisitorConversation', window.location.hash)
  await fetchConversation()
})
</script>