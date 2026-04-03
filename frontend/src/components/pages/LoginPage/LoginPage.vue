<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="bg-white shadow-md rounded-xl p-8 w-full max-w-md">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Login</h1>
      <p class="text-gray-600 mb-6">Sign in to manage dealership inventory</p>

      <p v-if="error" class="mb-4 text-red-600 font-medium">
        {{ error }}
      </p>

      <form @submit.prevent="login" class="space-y-4">
        <input
          v-model="email"
          type="email"
          placeholder="Email"
          class="w-full border rounded-lg px-4 py-2"
        />

        <input
          v-model="password"
          type="password"
          placeholder="Password"
          class="w-full border rounded-lg px-4 py-2"
        />

        <button
          type="submit"
          class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
          Login
        </button>
      </form>

      <div class="mt-6 text-sm text-gray-600">
        <p><strong>Admin:</strong> admin@dbcauto.nl / password123</p>
        <p><strong>Employee:</strong> employee@dbcauto.nl / password123</p>
      </div>

      <div class="mt-6">
        <a href="#/" class="text-blue-600 hover:underline">← Back to Inventory</a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { post } from '../../../utils/api.js'

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