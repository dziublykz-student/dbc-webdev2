<template>
  <div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full">
      <thead class="bg-gray-100">
        <tr>
          <th class="text-left px-4 py-3">ID</th>
          <th class="text-left px-4 py-3">Brand</th>
          <th class="text-left px-4 py-3">Model</th>
          <th class="text-left px-4 py-3">Year</th>
          <th class="text-left px-4 py-3">Price</th>
          <th class="text-left px-4 py-3">Status</th>
          <th v-if="isAdmin" class="text-left px-4 py-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="car in cars"
          :key="car.id"
          class="border-t"
        >
          <td class="px-4 py-3">{{ car.id }}</td>
          <td class="px-4 py-3">{{ car.brand }}</td>
          <td class="px-4 py-3">{{ car.model }}</td>
          <td class="px-4 py-3">{{ car.year }}</td>
          <td class="px-4 py-3">€{{ Number(car.price).toLocaleString() }}</td>
          <td class="px-4 py-3">{{ car.status }}</td>

          <td v-if="isAdmin" class="px-4 py-3">
            <button
              type="button"
              class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors mr-2"
              @click="$emit('edit', car)"
            >
              Edit
            </button>

            <button
              type="button"
              class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
              @click="$emit('delete', car.id)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  cars: {
    type: Array,
    default: () => [],
  },
  isAdmin: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['edit', 'delete'])
</script>