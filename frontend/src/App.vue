<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import HomePage from './components/pages/HomePage/HomePage.vue'
import CarInventoryPage from './components/pages/CarInventoryPage/CarInventoryPage.vue'
import CarDetailPage from './components/pages/CarDetailPage/CarDetailPage.vue'
import InquiryConversationPage from './components/pages/InquiryConversationPage/InquiryConversationPage.vue'
import FindInquiryPage from './components/pages/FindInquiryPage/FindInquiryPage.vue'
import AdminCarsPage from './components/pages/AdminCarsPage/AdminCarsPage.vue'
import AdminInquiriesPage from './components/pages/AdminInquiriesPage/AdminInquiriesPage.vue'
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

const isHomePage = computed(() => currentHash.value === '#/' || currentHash.value === '')
const isCarsPage = computed(() => currentHash.value === '#/cars')
const isLoginPage = computed(() => currentHash.value === '#/login')
const isAdminCarsPage = computed(() => currentHash.value === '#/admin/cars')
const isAdminInquiriesPage = computed(() => currentHash.value === '#/admin/inquiries')
const isFindInquiryPage = computed(() => currentHash.value === '#/find-inquiry')
const isInquiryConversationPage = computed(() => currentHash.value.startsWith('#/inquiries/'))
const isCarDetailPage = computed(() => currentHash.value.startsWith('#/cars/'))
const canAccessAdmin = computed(() => !!authToken.value)
</script>

<template>
  <LoginPage v-if="isLoginPage" />
  <AdminCarsPage v-else-if="isAdminCarsPage && canAccessAdmin" />
  <AdminInquiriesPage v-else-if="isAdminInquiriesPage && canAccessAdmin" />
  <LoginPage v-else-if="(isAdminCarsPage || isAdminInquiriesPage) && !canAccessAdmin" />
  <FindInquiryPage v-else-if="isFindInquiryPage" />
  <InquiryConversationPage v-else-if="isInquiryConversationPage" />
  <CarDetailPage v-else-if="isCarDetailPage" />
  <CarInventoryPage v-else-if="isCarsPage" :key="authToken || 'guest'" />
  <HomePage v-else-if="isHomePage" :key="`home-${authToken || 'guest'}`" />
  <HomePage v-else :key="`home-fallback-${authToken || 'guest'}`" />
</template>