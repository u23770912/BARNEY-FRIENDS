<!-- Header.vue -->
<template>
  <ClientOnly>
    <header class="bg-white border-b border-black px-8 py-4 flex items-center justify-between">
      <!-- Logo -->
      <NuxtLink
        to="/"
        class="text-3xl font-extrabold text-black select-none cursor-pointer hover:text-green-600"
      >
        CompareIT
      </NuxtLink>

      <!-- Auth Buttons -->
      <div class="flex space-x-6">
        <div v-if="isLoggedIn" class="flex space-x-6">
          <button @click="logout" type="button" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold">
            Logout
          </button>
        </div>

        <div v-else class="flex space-x-6">
          <!-- Login -->
          <NuxtLink to="/login" class="text-black font-semibold px-4 py-2 hover:text-green-600">
            Login
          </NuxtLink>

          <!-- Sign-Up -->
          <NuxtLink
            to="/signup"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold"
          >
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

onMounted(()=>{
  refreshAuth();
});

</script>