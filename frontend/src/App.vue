<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import HomePage from './components/pages/HomePage/HomePage.vue'
import CarInventoryPage from './components/pages/CarInventoryPage/CarInventoryPage.vue'
import CarDetailPage from './components/pages/CarDetailPage/CarDetailPage.vue'
import InquiryConversationPage from './components/pages/InquiryConversationPage/InquiryConversationPage.vue'
import AdminCarsPage from './components/pages/AdminCarsPage/AdminCarsPage.vue'
import AdminCarEditorPage from './components/pages/AdminCarEditorPage/AdminCarEditorPage.vue'
import AdminInquiriesPage from './components/pages/AdminInquiriesPage/AdminInquiriesPage.vue'
import AdminInquiryDetailPage from './components/pages/AdminInquiryDetailPage/AdminInquiryDetailPage.vue'
import LoginPage from './components/pages/LoginPage/LoginPage.vue'

const currentHash = ref(window.location.hash || '#/')
const authToken = ref(localStorage.getItem('token'))
const currentUser = ref(JSON.parse(localStorage.getItem('user') || 'null'))

const refreshAuth = () => {
  authToken.value = localStorage.getItem('token')
  currentUser.value = JSON.parse(localStorage.getItem('user') || 'null')
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

const isAdminCarEditorPage = computed(() =>
  currentHash.value === '#/admin/cars/new' ||
  currentHash.value.startsWith('#/admin/cars/edit/')
)

const isAdminCarsPage = computed(() => currentHash.value === '#/admin/cars')
const isAdminInquiriesPage = computed(() => currentHash.value === '#/admin/inquiries')
const isAdminInquiryDetailPage = computed(() =>
  currentHash.value.startsWith('#/admin/inquiries/')
)

const isInquiryConversationPage = computed(() =>
  currentHash.value.startsWith('#/inquiries/token/')
)

const isCarDetailPage = computed(() => currentHash.value.startsWith('#/cars/'))

const isLoggedIn = computed(() => !!authToken.value)
const isAdmin = computed(() => currentUser.value?.role === 'admin')
const isStaff = computed(() =>
  ['admin', 'employee'].includes(currentUser.value?.role || '')
)
</script>

<template>
  <LoginPage v-if="isLoginPage" />

  <AdminCarEditorPage v-else-if="isAdminCarEditorPage && isAdmin" />
  <LoginPage v-else-if="isAdminCarEditorPage && !isAdmin" />

  <AdminCarsPage v-else-if="isAdminCarsPage && isStaff" />
  <LoginPage v-else-if="isAdminCarsPage && !isStaff" />

  <AdminInquiryDetailPage v-else-if="isAdminInquiryDetailPage && isStaff" />
  <LoginPage v-else-if="isAdminInquiryDetailPage && !isStaff" />

  <AdminInquiriesPage v-else-if="isAdminInquiriesPage && isStaff" />
  <LoginPage v-else-if="isAdminInquiriesPage && !isStaff" />

  <InquiryConversationPage v-else-if="isInquiryConversationPage" />
  <CarDetailPage v-else-if="isCarDetailPage" />
  <CarInventoryPage v-else-if="isCarsPage" :key="`cars-${authToken || 'guest'}`" />
  <HomePage v-else-if="isHomePage" :key="`home-${authToken || 'guest'}`" />
  <HomePage v-else :key="`home-fallback-${authToken || 'guest'}`" />
</template>