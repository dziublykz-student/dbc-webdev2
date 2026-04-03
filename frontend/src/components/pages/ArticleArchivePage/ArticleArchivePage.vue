<template>
  <div>
    <div v-if="loading" class="min-h-screen flex items-center justify-center">
      <div class="text-center">
        <div
          class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"
        ></div>
        <p class="text-gray-600">Loading cars...</p>
      </div>
    </div>

    <div
      v-else-if="error"
      class="min-h-screen flex items-center justify-center"
    >
      <div class="text-center max-w-md">
        <div class="text-red-600 text-5xl mb-4">⚠️</div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
          Error Loading Cars
        </h2>
        <p class="text-gray-600 mb-4">{{ error }}</p>
        <button
          @click="fetchCars"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
          Try Again
        </button>
      </div>
    </div>

    <ArticleArchive
      v-else
      :articles="mappedCars"
      @article-click="handleCarClick"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import ArticleArchive from "../../templates/ArticleArchive/ArticleArchive.vue";
import { get } from "../../../utils/api.js";

const cars = ref([]);
const loading = ref(true);
const error = ref(null);

const mappedCars = computed(() =>
  cars.value.map((car) => ({
    id: car.id,
    title: `${car.brand} ${car.model}`,
    category: car.fuelType,
    content: car.description,
    year: car.year,
    transmission: car.transmission,
    mileage: `${car.mileage.toLocaleString()} km`,
    status: car.status,
    price: `€${Number(car.price).toLocaleString()}`,
    imageUrl: car.imageUrl,
  })),
);

const fetchCars = async () => {
  loading.value = true;
  error.value = null;

  try {
    const response = await get("/cars");

    if (!response.ok) {
      throw new Error(
        `Failed to fetch cars: ${response.status} ${response.statusText}`,
      );
    }

    const result = await response.json();
    cars.value = Array.isArray(result) ? result : (result.data ?? []);
  } catch (err) {
    console.error("Error fetching cars:", err);
    error.value =
      err.message || "Failed to load cars. Please try again later.";
    cars.value = [];
  } finally {
    loading.value = false;
  }
};

const handleCarClick = (carId) => {
  window.location.hash = `#/cars/${carId}`;
};

onMounted(() => {
  fetchCars();
});
</script>