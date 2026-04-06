<template>
  <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
    <div class="mb-6">
      <Heading :level="2" size="2xl" class="mb-2">
        Interested in this car?
      </Heading>
      <Text as="p" size="md" color="muted">
        Send a message and the dealership can follow up with you.
      </Text>
    </div>

    <p v-if="formError" class="mb-4 text-red-600 font-medium">
      {{ formError }}
    </p>

    <p v-if="formSuccess" class="mb-4 text-green-600 font-medium">
      {{ formSuccess }}
    </p>

    <form @submit.prevent="$emit('submit')" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <input
        :value="form.name"
        @input="$emit('update:form', { ...form, name: $event.target.value })"
        type="text"
        placeholder="Your name"
        class="border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
      />

      <input
        :value="form.email"
        @input="$emit('update:form', { ...form, email: $event.target.value })"
        type="email"
        placeholder="Your email"
        class="border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
      />

      <textarea
        :value="form.message"
        @input="$emit('update:form', { ...form, message: $event.target.value })"
        placeholder="Your message"
        class="border border-gray-200 rounded-xl px-4 py-3 md:col-span-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
        rows="5"
      ></textarea>

      <button
        type="submit"
        class="md:col-span-2 px-5 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition-colors font-medium"
      >
        Send Inquiry
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
  formError: {
    type: String,
    default: '',
  },
  formSuccess: {
    type: String,
    default: '',
  },
})

defineEmits(['submit', 'update:form'])
</script>