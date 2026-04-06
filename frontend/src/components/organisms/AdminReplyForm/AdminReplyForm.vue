<template>
  <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
    <div class="mb-6">
      <Heading :level="2" size="2xl" class="mb-2">
        Reply to inquiry
      </Heading>

      <Text as="p" size="md" color="muted">
        Send a message as staff and update the inquiry status.
      </Text>
    </div>

    <p v-if="error" class="mb-4 text-red-600 font-medium">
      {{ error }}
    </p>

    <p v-if="success" class="mb-4 text-green-600 font-medium">
      {{ success }}
    </p>

    <form @submit.prevent="$emit('submit')" class="space-y-4">
      <textarea
        :value="form.adminReply"
        @input="$emit('update:form', { ...form, adminReply: $event.target.value })"
        rows="5"
        placeholder="Write your reply..."
        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
      ></textarea>

      <select
        :value="form.status"
        @change="$emit('update:form', { ...form, status: $event.target.value })"
        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        <option value="new">new</option>
        <option value="handled">handled</option>
      </select>

      <button
        type="submit"
        class="px-5 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition-colors font-medium"
      >
        Send Reply
      </button>
    </form>
  </div>
</template>

<script setup>
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

defineProps({
  form: {
    type: Object,
    required: true,
  },
  error: {
    type: String,
    default: '',
  },
  success: {
    type: String,
    default: '',
  },
})

defineEmits(['submit', 'update:form'])
</script>