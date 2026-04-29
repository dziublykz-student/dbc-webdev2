<template>
  <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="w-full h-full">
        <img
            v-if="car.imageUrl"
            :src="car.imageUrl"
            :alt="`${car.brand} ${car.model}`"
            class="w-full h-full object-cover rounded-l-xl"
        />
        <div
            v-else
            class="w-full h-full flex items-center justify-center text-gray-500"
        >
            No image
        </div>
        </div>

        <div class="p-8">
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm px-3 py-1 rounded-full bg-gray-100 text-gray-700">
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

          <h1 class="text-3xl font-bold text-gray-900 mb-2">
            {{ car.brand }} {{ car.model }}
          </h1>

          <p class="text-3xl font-bold text-blue-600 mb-6">
            €{{ Number(car.price).toLocaleString() }}
          </p>

          <p class="text-gray-700 mb-6">
            {{ car.description }}
          </p>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
            <div class="p-4 bg-gray-50 rounded-lg">
              <strong>Year:</strong> {{ car.year }}
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
              <strong>Transmission:</strong> {{ car.transmission }}
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
              <strong>Mileage:</strong> {{ Number(car.mileage).toLocaleString() }} km
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
              <strong>Fuel Type:</strong> {{ car.fuelType }}
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