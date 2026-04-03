<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import ArticleArchivePage from './components/pages/ArticleArchivePage/ArticleArchivePage.vue'
import CarDetailPage from './components/pages/CarDetailPage/CarDetailPage.vue'
import AdminCarsPage from './components/pages/AdminCarsPage/AdminCarsPage.vue'
import LoginPage from './components/pages/LoginPage/LoginPage.vue'

const currentHash = ref(window.location.hash || '#/')
const authToken = ref(localStorage.getItem('token'))

const refreshAuth = () => {
  authToken.value = localStorage.getItem('token')
}

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  refreshAuth()
  window.location.hash = '#/'
}

const updateHash = () => {
  currentHash.value = window.location.hash || '#/'
  refreshAuth()

  if (currentHash.value === '#/logout') {
    logout()
  }
}

onMounted(() => {
  window.addEventListener('hashchange', updateHash)
  window.addEventListener('focus', refreshAuth)
  updateHash()
})

onUnmounted(() => {
  window.removeEventListener('hashchange', updateHash)
  window.removeEventListener('focus', refreshAuth)
})

const isLoginPage = computed(() => currentHash.value === '#/login')
const isAdminCarsPage = computed(() => currentHash.value === '#/admin/cars')
const isCarDetailPage = computed(() => currentHash.value.startsWith('#/cars/'))
const canAccessAdmin = computed(() => !!authToken.value)
</script>

<template>
  <LoginPage v-if="isLoginPage" />
  <AdminCarsPage v-else-if="isAdminCarsPage && canAccessAdmin" />
  <LoginPage v-else-if="isAdminCarsPage && !canAccessAdmin" />
  <CarDetailPage v-else-if="isCarDetailPage" />
  <ArticleArchivePage v-else :key="authToken || 'guest'" />
</template>