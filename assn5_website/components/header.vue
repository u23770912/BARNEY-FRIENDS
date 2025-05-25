<!-- Header.vue -->
<template>
  <ClientOnly>
    <header class="bg-white border-b border-black px-8 py-4 flex items-center justify-between">
      <!-- Hamburger -->
      <button @click="drawerOpen = !drawerOpen" class="text-black hover:text-green-600 p-2 -ml-2"
        aria-label="Toggle navigation">
        <svg v-if="!drawerOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7" fill="none"
          stroke="currentColor" stroke-width="2">
          <path d="M3 6h18M3 12h18M3 18h18" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-7 h-7" fill="none"
          stroke="currentColor" stroke-width="2">
          <path d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <!-- Brand centred -->
      <NuxtLink to="/"
        class="flex-1 text-center text-3xl font-extrabold text-black cursor-pointer hover:text-green-600">
        CompareIT
      </NuxtLink>

      <!-- Auth Buttons -->
      <div class="flex space-x-6">
        <div v-if="isLoggedIn" class="flex space-x-6">
          <button @click="logout" type="button"
            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold">
            Logout
          </button>
        </div>

        <div v-else class="flex space-x-6">
          <!-- Login -->
          <NuxtLink to="/login" class="text-black font-semibold px-4 py-2 hover:text-green-600">
            Login
          </NuxtLink>

          <!-- Sign-Up -->
          <NuxtLink to="/signup" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold">
            Sign Up
          </NuxtLink>
        </div>
      </div>
    </header>
  </ClientOnly>
</template>

<script setup>
import { useAuth } from '~/composables/useAuth';

const { isLoggedIn, logout, refreshAuth } = useAuth();

onMounted(() => {
  refreshAuth();
});

</script>