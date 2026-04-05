<template>
  <div class="bg-white rounded-xl shadow-md p-6 mb-8">
    <Heading :level="2" size="xl" class="mb-4">
      {{ isEditing ? 'Edit Car' : 'Create New Car' }}
    </Heading>

    <p v-if="formError" class="mb-4 text-red-600 font-medium">
      {{ formError }}
    </p>

    <p v-if="formSuccess" class="mb-4 text-green-600 font-medium">
      {{ formSuccess }}
    </p>

    <form @submit.prevent="$emit('save')" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <input v-model="localForm.brand" type="text" placeholder="Brand" class="border rounded-lg px-4 py-2" />
      <input v-model="localForm.model" type="text" placeholder="Model" class="border rounded-lg px-4 py-2" />
      <input v-model="localForm.year" type="number" placeholder="Year" class="border rounded-lg px-4 py-2" />
      <input v-model="localForm.price" type="number" placeholder="Price" class="border rounded-lg px-4 py-2" />
      <input v-model="localForm.mileage" type="number" placeholder="Mileage" class="border rounded-lg px-4 py-2" />
      <input v-model="localForm.fuelType" type="text" placeholder="Fuel Type" class="border rounded-lg px-4 py-2" />
      <input v-model="localForm.transmission" type="text" placeholder="Transmission" class="border rounded-lg px-4 py-2" />
      <input v-model="localForm.status" type="text" placeholder="Status" class="border rounded-lg px-4 py-2" />
      <input v-model="localForm.imageUrl" type="text" placeholder="Image URL" class="border rounded-lg px-4 py-2 md:col-span-2" />
      <textarea
        v-model="localForm.description"
        placeholder="Description"
        class="border rounded-lg px-4 py-2 md:col-span-2"
        rows="4"
      ></textarea>

      <button
        type="submit"
        class="md:col-span-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
      >
        {{ isEditing ? 'Update Car' : 'Create Car' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import Heading from '../../atoms/Heading/Heading.vue'

const props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    default: false,
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

const emit = defineEmits(['save', 'update:form'])

const localForm = computed({
  get: () => props.form,
  set: (value) => emit('update:form', value),
})
</script>