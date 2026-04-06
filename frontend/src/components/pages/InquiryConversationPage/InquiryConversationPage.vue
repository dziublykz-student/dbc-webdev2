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
    <section class="bg-gradient-to-br from-black via-gray-900 to-gray-800 text-white">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-3xl">
          <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-400 mb-3">
            Inquiry Conversation
          </p>

          <Heading :level="1" size="3xl" class="mb-4">
            Stay in touch with the dealership
          </Heading>

          <p class="text-lg text-gray-300 leading-8">
            Use your inquiry ID and email to reopen this conversation and continue
            discussing the car with the dealership team.
          </p>
        </div>

        <a
          href="#/cars"
          class="inline-flex items-center text-blue-400 hover:text-blue-300 font-medium mb-6"
        >
          ← Back to Inventory
        </a>
      </div>
    </section>

    <section class="py-10 bg-gray-50">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 mb-8">
          <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
            <div>
              <Heading :level="2" size="2xl" class="mb-2">
                Inquiry #{{ conversation.id }}
              </Heading>

              <Text as="p" size="sm" color="muted" class="mb-2">
                Save this inquiry ID: <strong>{{ conversation.id }}</strong>
              </Text>

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
              Continue the conversation using the same email address.
            </Text>
          </div>

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
              class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

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
const email = ref('')
const followUpMessage = ref('')
const followUpError = ref('')
const followUpSuccess = ref('')

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

const goBack = () => {
  window.location.hash = '#/cars'
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
  await fetchConversation()
})
</script>