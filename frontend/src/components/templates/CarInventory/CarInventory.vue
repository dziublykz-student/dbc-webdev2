<template>
  <div>
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5 mb-8">
      <div class="flex items-center justify-between mb-4">
        <div>
          <Heading :level="2" size="xl">Filter cars</Heading>
          <Text as="p" size="sm" color="muted">
            Narrow down the inventory by brand, fuel type, or status.
          </Text>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input
          :value="filters.brand"
          @input="$emit('update:filters', { ...filters, brand: $event.target.value })"
          type="text"
          placeholder="Filter by brand"
          class="border border-gray-200 rounded-xl px-4 py-3"
        />

        <select
          :value="filters.fuelType"
          @change="$emit('update:filters', { ...filters, fuelType: $event.target.value })"
          class="border border-gray-200 rounded-xl px-4 py-3"
        >
          <option value="">All fuel types</option>
          <option value="Petrol">Petrol</option>
          <option value="Diesel">Diesel</option>
          <option value="Electric">Electric</option>
          <option value="Hybrid">Hybrid</option>
        </select>

        <select
          :value="filters.status"
          @change="$emit('update:filters', { ...filters, status: $event.target.value })"
          class="border border-gray-200 rounded-xl px-4 py-3"
        >
          <option value="">All statuses</option>
          <option value="Available">Available</option>
          <option value="Sold">Sold</option>
        </select>

        <div class="flex gap-3">
          <button
            type="button"
            class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700"
            @click="$emit('apply-filters')"
          >
            Apply
          </button>

          <button
            type="button"
            class="flex-1 px-4 py-3 bg-gray-200 text-gray-800 rounded-xl hover:bg-gray-300"
            @click="$emit('reset-filters')"
          >
            Reset
          </button>
        </div>
      </div>
    </div>

    <div class="mb-6 flex items-end justify-between">
      <div>
        <Heading :level="2" size="xl">Available cars</Heading>
        <Text as="p" size="sm" color="muted">
          Browse the current selection in our dealership.
        </Text>
      </div>

      <div class="text-sm text-gray-500">
        {{ articles.length }} car<span v-if="articles.length !== 1">s</span>
      </div>
    </div>

    <div
      v-if="articles && articles.length > 0"
      class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"
    >
      <CarCard
        v-for="car in articles"
        :key="car.id"
        :article="car"
        @click="handleArticleClick"
      />
    </div>

    <div
      v-else
      class="bg-white rounded-2xl shadow-md border border-gray-100 text-center py-14 px-6"
    >
      <Heading :level="3" size="lg" class="mb-2">No cars found</Heading>
      <Text as="p" size="md" color="muted">
        Try changing or resetting your filters.
      </Text>
    </div>
  </div>
</template>

<script setup>
import CarCard from '../../organisms/CarCard/CarCard.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

defineProps({
  articles: { type: Array, default: () => [] },
  filters: {
    type: Object,
    default: () => ({
      brand: '',
      fuelType: '',
      status: '',
    }),
  },
})

const emit = defineEmits(['article-click', 'apply-filters', 'reset-filters', 'update:filters'])

const handleArticleClick = (articleId) => {
  emit('article-click', articleId)
}
</script>