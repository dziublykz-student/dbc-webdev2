<template>
  <div class="space-y-4">
    <div
      v-for="message in messages"
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
          {{ message.senderType === 'admin' ? 'Staff' : customerName }}
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
</template>

<script setup>
defineProps({
  messages: {
    type: Array,
    default: () => [],
  },
  customerName: {
    type: String,
    default: 'Customer',
  },
})

const formatDate = (value) => {
  try {
    return new Date(value).toLocaleString()
  } catch {
    return value
  }
}
</script>