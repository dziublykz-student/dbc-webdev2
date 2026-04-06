<template>
  <MainLayout>
    <section class="bg-gradient-to-br from-black via-gray-900 to-gray-800 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="max-w-3xl">
          <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-400 mb-3">
            Staff Login
          </p>

          <Heading :level="1" size="3xl" class="mb-4">
            Sign in to manage the dealership
          </Heading>

          <p class="text-lg text-gray-300 leading-8">
            Admins and employees can access inventory management and inquiry handling
            from the staff dashboard.
          </p>
        </div>
      </div>
    </section>

    <section class="bg-gray-50 py-12">
      <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
          <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            <Heading :level="2" size="2xl" class="mb-2">Login</Heading>

            <Text as="p" size="md" color="muted" class="mb-6">
              Sign in to manage dealership inventory and respond to inquiries.
            </Text>

            <p v-if="error" class="mb-4 text-red-600 font-medium">
              {{ error }}
            </p>

            <form class="space-y-4" @submit.prevent="login">
              <input
                v-model="email"
                type="email"
                placeholder="Email"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />

              <input
                v-model="password"
                type="password"
                placeholder="Password"
                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />

              <button
                type="submit"
                class="w-full px-5 py-3 bg-gray-900 text-white rounded-xl hover:bg-black transition-colors font-medium"
              >
                Login
              </button>
            </form>

            <div class="mt-6">
              <a href="#/cars" class="text-blue-600 hover:underline">
                ← Back to Inventory
              </a>
            </div>
          </div>

          <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            <Heading :level="2" size="2xl" class="mb-4">
              Demo Accounts
            </Heading>

            <div class="space-y-4">
              <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5">
                <p class="text-sm text-gray-500 mb-1">Admin</p>
                <p class="text-gray-900 font-semibold">admin@dbcauto.nl</p>
                <p class="text-gray-700">password123</p>
              </div>

              <div class="rounded-2xl bg-gray-50 border border-gray-100 p-5">
                <p class="text-sm text-gray-500 mb-1">Employee</p>
                <p class="text-gray-900 font-semibold">employee@dbcauto.nl</p>
                <p class="text-gray-700">password123</p>
              </div>
            </div>

            <div class="mt-6">
              <Text as="p" size="md" color="muted">
                Admins can manage cars and inquiries. Employees can access inquiry handling.
              </Text>
            </div>
          </div>
        </div>
      </div>
    </section>
  </MainLayout>
</template>

<script setup>
import { ref } from 'vue'
import { post } from '../../../utils/api.js'
import MainLayout from '../../templates/MainLayout/MainLayout.vue'
import Heading from '../../atoms/Heading/Heading.vue'
import Text from '../../atoms/Text/Text.vue'

const email = ref('')
const password = ref('')
const error = ref('')

const login = async () => {
  error.value = ''

  if (!email.value || !password.value) {
    error.value = 'Please enter both email and password.'
    return
  }

  try {
    const response = await post('/auth/login', {
      email: email.value,
      password: password.value,
    })

    const result = await response.json()

    if (!response.ok) {
      throw new Error(result.error || 'Login failed.')
    }

    const token = result.data?.token ?? result.token
    const user = result.data?.user ?? result.user

    localStorage.setItem('token', token)
    localStorage.setItem('user', JSON.stringify(user))

    window.location.hash = '#/admin/cars'
  } catch (err) {
    console.error('Login error:', err)
    error.value = err.message || 'Login failed.'
  }
}
</script>