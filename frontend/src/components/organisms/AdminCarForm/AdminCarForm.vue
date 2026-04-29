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

    <form @submit.prevent="handleSave" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <input
        v-model="localForm.brand"
        type="text"
        placeholder="Brand"
        class="border rounded-lg px-4 py-2"
      />

      <input
        v-model="localForm.model"
        type="text"
        placeholder="Model"
        class="border rounded-lg px-4 py-2"
      />

      <input
        v-model.number="localForm.year"
        type="number"
        placeholder="Year"
        :min="1965"
        :max="currentYear"
        class="border rounded-lg px-4 py-2"
      />

      <input
        v-model.number="localForm.price"
        type="number"
        placeholder="Price"
        min="0"
        step="0.01"
        class="border rounded-lg px-4 py-2"
      />

      <input
        v-model.number="localForm.mileage"
        type="number"
        placeholder="Mileage"
        min="0"
        step="1"
        class="border rounded-lg px-4 py-2"
      />

      <select
        v-model="localForm.fuelType"
        class="border rounded-lg px-4 py-2"
      >
        <option value="">Select fuel type</option>
        <option value="Petrol">Petrol</option>
        <option value="Diesel">Diesel</option>
        <option value="Electric">Electric</option>
        <option value="Hybrid">Hybrid</option>
      </select>

      <select
        v-model="localForm.transmission"
        class="border rounded-lg px-4 py-2"
      >
        <option value="">Select transmission</option>
        <option value="Manual">Manual</option>
        <option value="Automatic">Automatic</option>
      </select>

      <select
        v-model="localForm.status"
        class="border rounded-lg px-4 py-2"
      >
        <option value="Available">Available</option>
        <option value="Sold">Sold</option>
      </select>

      <input
        v-model="localForm.imageUrl"
        type="text"
        placeholder="Image URL"
        class="border rounded-lg px-4 py-2 md:col-span-2"
      />

      <textarea
        v-model="localForm.description"
        placeholder="Description"
        rows="4"
        class="border rounded-lg px-4 py-2 md:col-span-2"
      ></textarea>

      <div class="md:col-span-2">
        <Button
          type="submit"
          :label="isEditing ? 'Update Car' : 'Create Car'"
          primary
        />
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Button from '../../atoms/Button/Button.vue'

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

const currentYear = new Date().getFullYear()

const localForm = ref({
  brand: '',
  model: '',
  year: '',
  price: '',
  mileage: '',
  fuelType: '',
  transmission: '',
  status: 'Available',
  imageUrl: '',
  description: '',
})

watch(
  () => props.form,
  (newForm) => {
    localForm.value = {
      brand: newForm?.brand ?? '',
      model: newForm?.model ?? '',
      year: newForm?.year ?? '',
      price: newForm?.price ?? '',
      mileage: newForm?.mileage ?? '',
      fuelType: newForm?.fuelType ?? '',
      transmission: newForm?.transmission ?? '',
      status: newForm?.status ?? 'Available',
      imageUrl: newForm?.imageUrl ?? '',
      description: newForm?.description ?? '',
    }
  },
  { immediate: true, deep: true }
)

watch(
  localForm,
  (newVal) => {
    emit('update:form', { ...newVal })
  },
  { deep: true }
)

const handleSave = () => {
  emit('save')
}
</script>