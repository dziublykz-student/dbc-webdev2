<template>
  <footer class="bg-black text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h3 class="text-xl font-bold mb-3">DBC Auto</h3>
          <p class="text-gray-300">
            Browse quality dealership cars, send inquiries, and stay in touch with our team.
          </p>
        </div>

        <div>
          <h4 class="text-lg font-semibold mb-3">Quick Links</h4>
          <ul class="space-y-2">
            <li v-for="link in quickLinks" :key="link.name">
              <a
                :href="link.href"
                class="text-gray-300 hover:text-white transition-colors"
              >
                {{ link.name }}
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-gray-800 mt-8 pt-6 text-sm text-gray-400 text-center">
          © {{ currentYear }} DBC Auto. All rights reserved.
      </div>
    </div>
  </footer>
</template>

<script setup>
defineProps({
  quickLinks: {
    type: Array,
    default: () => [],
  },
})
</script>