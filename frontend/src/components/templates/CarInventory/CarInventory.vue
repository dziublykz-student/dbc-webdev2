<script setup>
import { computed } from 'vue';
import Header from '../../organisms/Header/Header.vue';
import Footer from '../../organisms/Footer/Footer.vue';
import ArticleCard from '../../organisms/CarCard/CarCard.vue';
import Heading from '../../atoms/Heading/Heading.vue';
import Text from '../../atoms/Text/Text.vue';

const props = defineProps({
  articles: {
    type: Array,
    default: () => [],
  },
  filters: {
    type: Object,
    default: () => ({
      brand: '',
      fuelType: '',
      status: '',
    }),
  },
  navigationLinks: {
    type: Array,
    default: () => [
      { name: 'Home', href: '#/' },
      { name: 'Cars', href: '#/' },
    ],
  },
  footerQuickLinks: {
    type: Array,
    default: () => [
      { name: 'Home', href: '#/' },
      { name: 'Cars', href: '#/' },
      { name: 'Inventory', href: '#/' },
      { name: 'About', href: '#/' },
    ],
  },
  footerLegalLinks: {
    type: Array,
    default: () => [
      { name: 'Privacy Policy', href: '/privacy' },
      { name: 'Terms of Service', href: '/terms' },
      { name: 'Cookie Policy', href: '/cookies' },
    ],
  },
});

const emit = defineEmits(['article-click', 'apply-filters', 'reset-filters']);

const isLoggedIn = computed(() => !!localStorage.getItem('token'));

const computedNavigationLinks = computed(() => {
  const baseLinks = [
    { name: 'Home', href: '#/' },
    { name: 'Cars', href: '#/' },
  ];

  if (isLoggedIn.value) {
    return [
      ...baseLinks,
      { name: 'Admin', href: '#/admin/cars' },
      { name: 'Logout', href: '#/logout' },
    ];
  }

  return [
    ...baseLinks,
    { name: 'Login', href: '#/login' },
  ];
});

const handleArticleClick = (articleId) => {
  emit('article-click', articleId);
};
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header :navigation-links="computedNavigationLinks" />

    <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
      <div class="bg-white rounded-xl shadow-md p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input
            :value="filters.brand"
            @input="$emit('update:filters', { ...filters, brand: $event.target.value })"
            type="text"
            placeholder="Filter by brand"
            class="border rounded-lg px-4 py-2"
          />

          <select
            :value="filters.fuelType"
            @change="$emit('update:filters', { ...filters, fuelType: $event.target.value })"
            class="border rounded-lg px-4 py-2"
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
            class="border rounded-lg px-4 py-2"
          >
            <option value="">All statuses</option>
            <option value="Available">Available</option>
            <option value="Sold">Sold</option>
          </select>

          <div class="flex gap-2">
            <button
              type="button"
              class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
              @click="$emit('apply-filters')"
            >
              Apply
            </button>

            <button
              type="button"
              class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors"
              @click="$emit('reset-filters')"
            >
              Reset
            </button>
          </div>
        </div>
      </div>

      <div class="mb-8">
        <Heading :level="1" size="3xl" class="mb-2">
          Car Inventory
        </Heading>
        <Text as="p" size="lg" color="muted">
          Browse our available dealership cars
        </Text>
      </div>

      <div
        v-if="articles && articles.length > 0"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
      >
        <ArticleCard
          v-for="article in articles"
          :key="article.id"
          :article="article"
          @click="handleArticleClick"
        />
      </div>

      <div v-else class="text-center py-12">
        <Text as="p" size="lg" color="muted">
          No cars found.
        </Text>
      </div>
    </main>

    <Footer
      :quick-links="footerQuickLinks"
      :legal-links="footerLegalLinks"
    />
  </div>
</template>