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
import { computed } from 'vue'
import Header from '../../organisms/Header/Header.vue'
import Footer from '../../organisms/Footer/Footer.vue'

const footerQuickLinks = [
  { name: 'Home', href: '#/' },
  { name: 'Cars', href: '#/cars' },
  { name: 'Find Inquiry', href: '#/find-inquiry' },
  { name: 'Login', href: '#/login' },
]

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
    { name: 'Find Inquiry', href: '#/find-inquiry' },
  ]

  if (isLoggedIn.value) {
    return [
      ...baseLinks,
      { name: 'Admin Cars', href: '#/admin/cars' },
      { name: 'Admin Inquiries', href: '#/admin/inquiries' },
      { name: 'Logout', href: '#/logout' },
    ]
  }

  return [
    ...baseLinks,
    { name: 'Login', href: '#/login' },
  ]
})
</script>