<template>
  <LoadingState
    v-if="loading"
    message="Loading conversation..."
  />

  <ErrorState
    v-else-if="error"
    title="Unable to Load Conversation"
    :message="error"
    button-text="Back to Inventory"
    @retry="goBack"
  />

  <MainLayout v-else-if="conversation">
    <section class="bg-gray-50 py-10">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <a
          href="#/cars"
          class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium mb-6"
        >
          ← Back to Inventory
        </a>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 mb-8">
          <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
            <div>
              <Heading :level="2" size="2xl" class="mb-2">
                Your inquiry conversation
              </Heading>

              <Text as="p" size="sm" color="muted">
                {{ conversation.name }} • {{ conversation.email }}
              </Text>
            </div>

            <div class="text-left md:text-right">
              <Text as="p" size="sm" color="muted" class="mb-2">
                {{ formatDate(conversation.createdAt) }}
              </Text>

              <span
                class="text-xs font-semibold px-3 py-1 rounded-full"
                :class="conversation.status === 'handled'
                  ? 'bg-green-100 text-green-700'
                  : 'bg-yellow-100 text-yellow-700'"
              >
                {{ conversation.status }}
              </span>
            </div>
          </div>

          <div class="space-y-4">
            <div
              v-for="message in conversation.messages"
              :key="message.id"
              class="flex"
              :class="message.senderType === 'admin' ? 'justify-end' : 'justify-start'"
            >
              <div
                class="max-w-[80%] rounded-2xl px-5 py-4 shadow-sm"
                :class="message.senderType === 'admin'
                  ? 'bg-blue-600 text-white'
                  : 'bg-gray-100 text-gray-900 border border-gray-200'"
              >
                <div class="text-xs font-semibold opacity-80 mb-2">
                  {{ message.senderType === 'admin' ? 'Dealership' : 'You' }}
                </div>

                <div class="whitespace-pre-wrap break-words leading-7">
                  {{ message.message }}
                </div>

                <div
                  class="text-[11px] mt-3"
                  :class="message.senderType === 'admin' ? 'text-blue-100' : 'text-gray-500'"
                >
                  {{ formatDate(message.createdAt) }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
          <div class="mb-6">
            <Heading :level="2" size="2xl" class="mb-2">
              Send a follow-up
            </Heading>

            <Text as="p" size="md" color="muted">
              Continue the conversation with another message.
            </Text>
          </div>

          <p v-if="followUpError" class="mb-4 text-red-600 font-medium">
            {{ followUpError }}
          </p>

          <p v-if="followUpSuccess" class="mb-4 text-green-600 font-medium">
            {{ followUpSuccess }}
          </p>

          <form @submit.prevent="submitFollowUp" class="space-y-4">
            <textarea
              v-model="followUpMessage"
              placeholder="Write your follow-up message"
              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
              rows="5"
            ></textarea>

            <button
              type="submit"
              class="px-5 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition-colors font-medium"
            >
              Send Follow-up
            </button>
          </form>
        </div>
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
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const conversation = ref(null)
const loading = ref(true)
const error = ref(null)
const followUpMessage = ref('')
const followUpError = ref('')
const followUpSuccess = ref('')

const getTokenFromHash = () => {
  const parts = window.location.hash.split('/')
  return parts[3] || ''
}

const goBack = () => {
  window.location.hash = '#/cars'
}

const fetchConversation = async () => {
  loading.value = true
  error.value = null

  const token = getTokenFromHash()

  if (!token) {
    error.value = 'Missing inquiry token.'
    loading.value = false
    return
  }

  try {
    const response = await get(`/inquiries/token/${token}`)
    const result = await response.json()

    if (!response.ok) {
      throw new Error(result.error || `Failed to load conversation: ${response.status} ${response.statusText}`)
    }

    conversation.value = result.data ?? result
    localStorage.setItem('lastVisitorConversation', window.location.hash)
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

  const token = getTokenFromHash()

  if (!token) {
    followUpError.value = 'Missing inquiry token.'
    return
  }

  if (!followUpMessage.value.trim()) {
    followUpError.value = 'Please write a follow-up message.'
    return
  }

  try {
    const response = await post(`/inquiries/token/${token}/messages`, {
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
  await fetchConversation()
})
</script>