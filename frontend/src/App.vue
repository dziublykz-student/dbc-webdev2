<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import ArticleArchivePage from './components/pages/ArticleArchivePage/ArticleArchivePage.vue'
import CarDetailPage from './components/pages/CarDetailPage/CarDetailPage.vue'

const currentHash = ref(window.location.hash || '#/')

const updateHash = () => {
  currentHash.value = window.location.hash || '#/'
}

onMounted(() => {
  window.addEventListener('hashchange', updateHash)
  updateHash()
})

onUnmounted(() => {
  window.removeEventListener('hashchange', updateHash)
})

const isCarDetailPage = computed(() => currentHash.value.startsWith('#/cars/'))
</script>

<template>
  <CarDetailPage v-if="isCarDetailPage" />
  <ArticleArchivePage v-else />
</template>