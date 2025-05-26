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
      <div class="absolute inset-0 flex justify-center items-center pointer-events-none">
        <NuxtLink to="/"
          class="text-3xl font-extrabold text-black cursor-pointer hover:text-green-600 transition z-0">
          CompareIT
        </NuxtLink>
      </div>

      <!-- Auth Buttons -->
      <div class="flex space-x-6 z-10">
        <div v-if="isLoggedIn" class="flex space-x-6">
          <button @click="logout" type="button"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold transition">
            Logout
          </button>
        </div>
        <div v-else class="flex space-x-6">
          <NuxtLink to="/login" class="text-black font-semibold px-4 py-2 hover:text-green-600 transition">Login</NuxtLink>
          <NuxtLink to="/signup"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold transition">
            Sign Up
          </NuxtLink>
        </div>
      </div>
    </header>
  </ClientOnly>

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
          >
            Home
          </NuxtLink>
        </li>
        <li>
          <NuxtLink
            to="/dashboard"
            class="block py-2 px-4 rounded hover:bg-gray-100 transition"
          >
            Top Rated Products
          </NuxtLink>
        </li>
        <li v-if="isLoggedIn">
          <NuxtLink
            to="/wishlist"
            class="block py-2 px-4 rounded hover:bg-gray-100 transition"
          >
            My Wishlist
          </NuxtLink>
        </li>
      </ul>
    </nav>
  </transition>

  <!-- Overlay when drawer is open -->
  <div v-if="drawerOpen" @click="drawerOpen = false"
    class="fixed inset-0 bg-black opacity-50 z-40 md:hidden"></div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '~/composables/useAuth'

const drawerOpen = ref(false)
const { isLoggedIn, logout, refreshAuth } = useAuth()
const router = useRouter()

onMounted(() => {
  refreshAuth()

  // Escape key closes drawer
  const handleKeyDown = (e) => {
    if (e.key === 'Escape') {
      drawerOpen.value = false
    }
  }

  window.addEventListener('keydown', handleKeyDown)

  // Swipe-to-close support
  let touchStartX = 0
  const handleTouchStart = (e) => {
    touchStartX = e.touches[0].clientX
  }
  const handleTouchEnd = (e) => {
    const touchEndX = e.changedTouches[0].clientX
    if (touchStartX - touchEndX > 50) {
      drawerOpen.value = false // swipe left
    }
  }

  window.addEventListener('touchstart', handleTouchStart)
  window.addEventListener('touchend', handleTouchEnd)

  onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeyDown)
    window.removeEventListener('touchstart', handleTouchStart)
    window.removeEventListener('touchend', handleTouchEnd)
  })
})

// Close drawer on route change
watch(
  () => router.currentRoute.value.fullPath,
  () => {
    drawerOpen.value = false
  }
)
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
</style>
