<template>
  <header class="bg-black text-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <a
          href="#/"
          class="text-2xl font-bold text-white hover:text-blue-400 transition-colors"
        >
          DBC Auto
        </a>

        <nav class="flex items-center gap-6">
          <a
            v-for="link in navigationLinks"
            :key="link.name"
            :href="link.href"
            class="text-white hover:text-blue-400 transition-colors font-medium"
          >
            {{ link.name }}
          </a>
        </nav>
      </div>
    </div>
  </header>
</template>

<script setup>
defineProps({
  navigationLinks: {
    type: Array,
    default: () => [],
  },
})
</script>