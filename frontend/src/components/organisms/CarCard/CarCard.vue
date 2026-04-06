<template>
  <article
    class="group bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
  >
    <div class="relative h-56 bg-gray-200 overflow-hidden">
      <img
        v-if="article.imageUrl"
        :src="article.imageUrl"
        :alt="article.title"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
      />

      <div
        v-else
        class="w-full h-full flex items-center justify-center text-gray-500"
      >
        No image
      </div>

      <div class="absolute inset-x-0 top-0 p-4 flex items-start justify-between">
        <CategoryBadge :category="article.category" />

        <span
          class="text-xs font-semibold px-3 py-1 rounded-full shadow-sm"
          :class="article.status === 'Available'
            ? 'bg-green-100 text-green-700'
            : 'bg-red-100 text-red-700'"
        >
          {{ article.status }}
        </span>
      </div>
    </div>

    <div class="p-6">
      <Heading :level="3" size="xl" class="mb-3">
        <button
          type="button"
          class="text-left text-gray-900 group-hover:text-blue-600 transition-colors"
          @click="$emit('click', article.id)"
        >
          {{ article.title }}
        </button>
      </Heading>

      <Text as="p" size="sm" color="muted" class="mb-5 line-clamp-3 leading-6">
        {{ truncatedContent }}
      </Text>

      <div class="grid grid-cols-1 gap-2 mb-5 text-sm text-gray-700">
        <div class="flex justify-between border-b border-gray-100 pb-2">
          <span class="font-medium text-gray-500">Year</span>
          <span class="font-semibold text-gray-900">{{ article.year }}</span>
        </div>

        <div class="flex justify-between border-b border-gray-100 pb-2">
          <span class="font-medium text-gray-500">Transmission</span>
          <span class="font-semibold text-gray-900">{{ article.transmission }}</span>
        </div>

        <div class="flex justify-between">
          <span class="font-medium text-gray-500">Mileage</span>
          <span class="font-semibold text-gray-900">{{ article.mileage }}</span>
        </div>
      </div>

      <div class="flex items-center justify-between">
        <div class="text-2xl font-bold text-blue-600">
          {{ article.price }}
        </div>

        <button
          type="button"
          class="px-4 py-2 rounded-xl bg-gray-900 text-white font-medium hover:bg-black transition-colors"
          @click="$emit('click', article.id)"
        >
          View Car
        </button>
      </div>
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'
import CategoryBadge from '../../molecules/CategoryBadge/CategoryBadge.vue'

const props = defineProps({
  article: {
    type: Object,
    required: true,
    validator: (value) => {
      return value.id && value.title && value.category && value.content
    },
  },
})

defineEmits(['click'])

const truncatedContent = computed(() => {
  const maxLength = 150
  if (props.article.content.length <= maxLength) {
    return props.article.content
  }
  return props.article.content.substring(0, maxLength) + '...'
})
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>