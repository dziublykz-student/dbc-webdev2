<template>
  <MainLayout>
    <section class="bg-gradient-to-br from-black via-gray-900 to-gray-800 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-400 mb-4">
              Welcome to DBC Auto
            </p>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
              Find your next car with confidence
            </h1>

            <p class="text-lg text-gray-300 mb-8 max-w-2xl leading-8">
              Browse quality vehicles, contact the dealership directly, and continue
              your inquiry conversation in one simple place.
            </p>

            <div class="flex flex-wrap gap-4">
              <a
                href="#/cars"
                class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition-colors shadow-lg"
              >
                Browse Cars
              </a>

              <a
                v-if="lastVisitorConversation"
                :href="lastVisitorConversation"
                class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white/10 text-white font-semibold border border-white/20 hover:bg-white/20 transition-colors"
              >
                Open Recent Inquiry
              </a>
            </div>
          </div>

          <div class="relative">
            <div class="absolute -inset-4 bg-blue-500/20 blur-3xl rounded-full"></div>
            <div class="relative bg-white/5 border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
              <img
                src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1200&q=80"
                alt="Car showroom"
                class="w-full h-[420px] object-cover"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-600 mb-3">
            Why choose us
          </p>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            A simple car buying experience
          </h2>
          <p class="text-gray-600 max-w-2xl mx-auto leading-7">
            DBC Auto combines car inventory browsing with direct communication,
            making it easier to ask questions and stay updated.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-7 hover:-translate-y-1 hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold mb-5">
              1
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">
              Quality Inventory
            </h3>
            <p class="text-gray-600 leading-7">
              Browse a selection of cars with pricing, mileage, transmission,
              fuel type, and availability clearly shown.
            </p>
          </div>

          <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-7 hover:-translate-y-1 hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold mb-5">
              2
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">
              Direct Inquiries
            </h3>
            <p class="text-gray-600 leading-7">
              Send a message about any vehicle and continue the conversation
              later through the inquiry page.
            </p>
          </div>

          <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-7 hover:-translate-y-1 hover:shadow-lg transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold mb-5">
              3
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">
              Staff Support
            </h3>
            <p class="text-gray-600 leading-7">
              The dealership team can view inquiries, manage cars, and respond
              efficiently through the admin area.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="pb-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl overflow-hidden shadow-xl bg-gradient-to-r from-blue-600 to-blue-800 text-white">
          <div class="px-8 py-12 md:px-12 md:py-14">
            <div class="max-w-3xl">
              <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-100 mb-3">
                Ready to start?
              </p>
              <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Explore the inventory today
              </h2>
              <p class="text-blue-100 text-lg mb-8 leading-8">
                View available vehicles and send an inquiry as soon as you find
                a car that interests you.
              </p>

              <div class="flex flex-wrap gap-4">
                <a
                  href="#/cars"
                  class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-white text-blue-700 font-semibold hover:bg-gray-100 transition-colors"
                >
                  View Inventory
                </a>

                <a
                  v-if="lastVisitorConversation"
                  :href="lastVisitorConversation"
                  class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-white/30 text-white font-semibold hover:bg-white/10 transition-colors"
                >
                  Open Recent Inquiry
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </MainLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import MainLayout from '../../templates/MainLayout/MainLayout.vue'

const lastVisitorConversation = ref('')

onMounted(() => {
  lastVisitorConversation.value = localStorage.getItem('lastVisitorConversation') || ''
})
</script>