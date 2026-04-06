<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <Header :navigation-links="computedNavigationLinks" />

    <main class="flex-1">
      <slot />
    </main>

    <Footer
      :quick-links="footerQuickLinks"
      :legal-links="footerLegalLinks"
    />
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'

const lastVisitorConversation = ref('')

const updateRecentInquiry = () => {
  lastVisitorConversation.value = localStorage.getItem('lastVisitorConversation') || ''
}

const footerQuickLinks = computed(() => {
  const links = [
    { name: 'Home', href: '#/' },
    { name: 'Cars', href: '#/cars' },
  ]

  if (lastVisitorConversation.value) {
    links.push({ name: 'Recent Inquiry', href: lastVisitorConversation.value })
  }

  links.push({ name: 'Login', href: '#/login' })

  return links
})

const footerLegalLinks = [
  { name: 'Privacy Policy', href: '/privacy' },
  { name: 'Terms of Service', href: '/terms' },
  { name: 'Cookie Policy', href: '/cookies' },
]

const isLoggedIn = computed(() => !!localStorage.getItem('token'))

const computedNavigationLinks = computed(() => {
  const baseLinks = [
    { name: 'Home', href: '#/' },
    { name: 'Cars', href: '#/cars' },
  ]

  if (lastVisitorConversation.value && !isLoggedIn.value) {
    baseLinks.push({
      name: 'Open Recent Inquiry',
      href: lastVisitorConversation.value,
    })
  }

  if (isLoggedIn.value) {
    return [
      ...baseLinks,
      { name: 'Admin', href: '#/admin/cars' },
      { name: 'Logout', href: '#/logout' },
    ]
  }

  return [
    ...baseLinks,
    { name: 'Login', href: '#/login' },
  ]
})

onMounted(() => {
  updateRecentInquiry()
  window.addEventListener('storage', updateRecentInquiry)
  window.addEventListener('focus', updateRecentInquiry)
  window.addEventListener('hashchange', updateRecentInquiry)
})

onUnmounted(() => {
  window.removeEventListener('storage', updateRecentInquiry)
  window.removeEventListener('focus', updateRecentInquiry)
  window.removeEventListener('hashchange', updateRecentInquiry)
})
</script>