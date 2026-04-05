<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <a href="#/" class="text-blue-600 hover:underline mb-6 inline-block">
        ← Back to Inventory
      </a>

      <div class="bg-white rounded-xl shadow-md p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
          Find your inquiry
        </h1>
        <p class="text-gray-600 mb-6">
          Enter your inquiry ID and the email you used to reopen your conversation.
        </p>

        <p v-if="error" class="mb-4 text-red-600 font-medium">
          {{ error }}
        </p>

        <form @submit.prevent="openInquiry" class="space-y-4">
          <input
            v-model="inquiryId"
            type="number"
            placeholder="Inquiry ID"
            class="w-full border rounded-lg px-4 py-2"
          />

          <input
            v-model="email"
            type="email"
            placeholder="Your email"
            class="w-full border rounded-lg px-4 py-2"
          />

          <button
            type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            Open Inquiry
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const inquiryId = ref('')
const email = ref('')
const error = ref('')

const openInquiry = () => {
  error.value = ''

  if (!String(inquiryId.value).trim() || !String(email.value).trim()) {
    error.value = 'Please fill in both inquiry ID and email.'
    return
  }

  const encodedEmail = encodeURIComponent(email.value.trim())
  window.location.hash = `#/inquiries/${inquiryId.value}?email=${encodedEmail}`
}
</script>