<template>
  <div class="min-h-screen flex items-center justify-center">
    <div class="text-center">
      <div
        class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"
      ></div>
      <Text as="p" size="lg" color="muted">
        {{ message }}
      </Text>
    </div>
  </div>
</template>

<script setup>
import Text from '../../atoms/Text/Text.vue'

defineProps({
  message: {
    type: String,
    default: 'Loading...',
  },
})
</script>