<template>
  <article
    class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden"
  >
    <div class="h-48 bg-gray-200 overflow-hidden">
      <img
        v-if="article.imageUrl"
        :src="article.imageUrl"
        :alt="article.title"
        class="w-full h-full object-cover"
      />
      <div
        v-else
        class="w-full h-full flex items-center justify-center text-gray-500"
      >
        No image
      </div>
    </div>

    <div class="p-6">
      <div class="flex items-start justify-between mb-3">
        <CategoryBadge :category="article.category" />
        <span
          class="text-xs font-semibold px-2 py-1 rounded-full"
          :class="article.status === 'Available'
            ? 'bg-green-100 text-green-700'
            : 'bg-red-100 text-red-700'"
        >
          {{ article.status }}
        </span>
      </div>

      <Heading :level="3" size="xl" class="mb-3">
        <button
          type="button"
          class="hover:text-blue-600 transition-colors text-left"
          @click="$emit('click', article.id)"
        >
          {{ article.title }}
        </button>
      </Heading>

      <Text as="p" size="sm" color="muted" class="mb-4 line-clamp-3">
        {{ truncatedContent }}
      </Text>

      <div class="space-y-2 text-sm text-gray-700 mb-4">
        <div><strong>Year:</strong> {{ article.year }}</div>
        <div><strong>Transmission:</strong> {{ article.transmission }}</div>
        <div><strong>Mileage:</strong> {{ article.mileage }}</div>
      </div>

      <div class="text-xl font-bold text-blue-600">
        {{ article.price }}
      </div>
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue';
import Heading from '../../atoms/Heading/Heading.vue';
import Text from '../../atoms/Text/Text.vue';
import CategoryBadge from '../../molecules/CategoryBadge/CategoryBadge.vue';

const props = defineProps({
  article: {
    type: Object,
    required: true,
    validator: (value) => {
      return value.id && value.title && value.category && value.content;
    },
  },
});

defineEmits(['click']);

const truncatedContent = computed(() => {
  const maxLength = 150;
  if (props.article.content.length <= maxLength) {
    return props.article.content;
  }
  return props.article.content.substring(0, maxLength) + '...';
});
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>