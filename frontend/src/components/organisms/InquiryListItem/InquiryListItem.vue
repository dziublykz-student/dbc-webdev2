<template>
  <a
    :href="`#/admin/inquiries/${inquiry.id}`"
    class="block bg-white rounded-2xl shadow-md border border-gray-100 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all"
  >
    <div class="flex items-start justify-between gap-4">
      <div class="min-w-0">
        <div class="flex items-center gap-3 mb-1">
          <Heading :level="3" size="lg" class="truncate">
            {{ inquiry.name }}
          </Heading>

          <span
            class="text-xs font-semibold px-2 py-1 rounded-full shrink-0"
            :class="inquiry.status === 'handled'
              ? 'bg-green-100 text-green-700'
              : 'bg-yellow-100 text-yellow-700'"
          >
            {{ inquiry.status }}
          </span>
        </div>

        <Text as="p" size="sm" color="muted" class="mb-2">
          {{ inquiry.email }} • Car ID: {{ inquiry.carId }}
        </Text>

        <p class="text-sm text-gray-700 truncate">
          {{ lastMessage }}
        </p>
      </div>

      <div class="text-right shrink-0">
        <Text as="p" size="sm" color="muted">
          {{ formattedDate }}
        </Text>
      </div>
    </div>
  </a>
</template>

<script setup>
import { computed } from 'vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const props = defineProps({
  inquiry: {
    type: Object,
    required: true,
  },
})

const lastMessage = computed(() => {
  const messages = props.inquiry.messages || []
  if (!messages.length) return 'No messages yet.'
  return messages[messages.length - 1]?.message || 'No messages yet.'
})

const formattedDate = computed(() => {
  try {
    return new Date(props.inquiry.createdAt).toLocaleString()
  } catch {
    return props.inquiry.createdAt
  }
})
</script>