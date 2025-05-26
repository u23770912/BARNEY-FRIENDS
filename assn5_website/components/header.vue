<template>
  <ClientOnly>
    <header class="bg-white border-b border-black px-8 py-4 flex items-center justify-between relative">
      <!-- Hamburger Button -->
      <button @click="drawerOpen = !drawerOpen" class="text-black hover:text-green-600 p-2 -ml-2 z-10"
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

      <!-- Centered Logo -->
      <div class="absolute inset-0 flex justify-center items-center">
        <NuxtLink to="/"
          class="text-3xl font-extrabold text-black cursor-pointer hover:text-green-600 transition">
          CompareIT
        </NuxtLink>
      </div>

      <!-- Auth Buttons -->
      <div class="flex space-x-6">
        <div v-if="isLoggedIn" class="flex space-x-6">
          <button @click="logout" type="button"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold transition">
            Logout
          </button>
        </div>

        <div v-else class="flex space-x-6">
          <NuxtLink to="/login" class="text-black font-semibold px-4 py-2 hover:text-green-600 transition">Login</NuxtLink>
          <NuxtLink to="/signup" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold transition">
            Sign Up
          </NuxtLink>
        </div>
      </div>

      <!-- Drawer Navigation -->
      <transition name="slide">
        <nav v-show="drawerOpen"
          class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg z-50 flex flex-col pt-16 px-6 pb-10 overflow-y-auto">
          <!-- Close Button -->
          <button @click="drawerOpen = false" class="absolute top-5 right-4 text-black">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="none"
              stroke="currentColor" stroke-width="2">
              <path d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <!-- Navigation Links -->
          <ul class="space-y-6 mt-4">
            <li>
              <NuxtLink
                to="/"
                class="block py-2 px-4 rounded hover:bg-gray-100 transition"
                @click.native="drawerOpen = false"
              >
                Home
              </NuxtLink>
            </li>
            <li>
              <NuxtLink
                to="/dashboard"
                class="block py-2 px-4 rounded hover:bg-gray-100 transition"
                @click.native="drawerOpen = false"
              >
                Top Rated Products
              </NuxtLink>
            </li>
            <li v-if="isLoggedIn">
              <NuxtLink
                to="/wishlist"
                class="block py-2 px-4 rounded hover:bg-gray-100 transition"
                @click.native="drawerOpen = false"
              >
                My Wishlist
              </NuxtLink>
            </li>
          </ul>

          <!-- Divider -->
          <hr class="my-6 border-gray-200" />

          <!-- Auth Section in Drawer -->
          <div v-if="isLoggedIn" class="mt-auto pt-6 border-t border-gray-200">
            <button
              @click="logout"
              class="w-full text-left block py-2 px-4 rounded hover:bg-red-100 text-red-600 font-semibold transition"
            >
              Logout
            </button>
          </div>

          <div v-else class="mt-auto pt-6 border-t border-gray-200">
            <NuxtLink
              to="/login"
              class="block py-2 px-4 rounded hover:bg-gray-100 transition"
              @click.native="drawerOpen = false"
            >
              Login
            </NuxtLink>
            <NuxtLink
              to="/signup"
              class="block py-2 px-4 rounded hover:bg-gray-100 transition"
              @click.native="drawerOpen = false"
            >
              Sign Up
            </NuxtLink>
          </div>
        </nav>
      </transition>

      <!-- Overlay when drawer is open -->
      <div v-if="drawerOpen" @click="drawerOpen = false"
        class="fixed inset-0 bg-black opacity-50 z-40 md:hidden"></div>
    </header>
  </ClientOnly>
</template>

<script setup>
import { ref } from 'vue';
import { useAuth } from '~/composables/useAuth';

const drawerOpen = ref(false);
const { isLoggedIn, logout, refreshAuth } = useAuth();

onMounted(() => {
  refreshAuth();
});
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  transform: translateX(-100%);
}

/* Ensure overlay doesn't interfere with scrolling */
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 40;
}
</style>