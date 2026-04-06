<template>
  <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-2">
      <div class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-[420px] flex items-center justify-center p-8">
        <img
          v-if="car.imageUrl"
          :src="car.imageUrl"
          :alt="`${car.brand} ${car.model}`"
          class="max-w-full max-h-[340px] object-contain"
        />
        <div
          v-else
          class="w-full h-full flex items-center justify-center text-gray-500"
        >
          No image
        </div>
      </div>

      <div class="p-8 lg:p-10">
        <div class="flex items-center justify-between mb-5">
          <span class="text-sm px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-medium">
            {{ car.fuelType }}
          </span>

          <span
            class="text-sm font-semibold px-3 py-1 rounded-full"
            :class="car.status === 'Available'
              ? 'bg-green-100 text-green-700'
              : 'bg-red-100 text-red-700'"
          >
            {{ car.status }}
          </span>
        </div>

        <Heading :level="1" size="3xl" class="mb-3">
          {{ car.brand }} {{ car.model }}
        </Heading>

        <p class="text-3xl font-bold text-blue-600 mb-6">
          €{{ Number(car.price).toLocaleString() }}
        </p>

        <Text as="p" size="md" color="muted" class="leading-7 mb-8">
          {{ car.description }}
        </Text>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
            <p class="text-sm text-gray-500 mb-1">Year</p>
            <p class="text-lg font-semibold text-gray-900">{{ car.year }}</p>
          </div>

          <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
            <p class="text-sm text-gray-500 mb-1">Transmission</p>
            <p class="text-lg font-semibold text-gray-900">{{ car.transmission }}</p>
          </div>

          <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
            <p class="text-sm text-gray-500 mb-1">Mileage</p>
            <p class="text-lg font-semibold text-gray-900">
              {{ Number(car.mileage).toLocaleString() }} km
            </p>
          </div>

          <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
            <p class="text-sm text-gray-500 mb-1">Fuel Type</p>
            <p class="text-lg font-semibold text-gray-900">{{ car.fuelType }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

defineProps({
  car: {
    type: Object,
    required: true,
  },
})
</script>