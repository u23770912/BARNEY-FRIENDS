<template>
  <header class="bg-white border-b border-black px-8 py-4 flex items-center justify-between">
    <!-- Logo -->
    <div class="text-3xl font-extrabold text-black select-none cursor-default">CompareIT</div>

    <!-- Navigation -->
    <nav class="flex space-x-12 text-black font-semibold">
      <!-- Mens -->
      <div class="relative group">
        <div class="flex items-center gap-1 hover:text-green-600 cursor-pointer">
          Mens
          <svg
            class="w-4 h-4 mt-1 transition-transform group-hover:rotate-180"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
          >
            <path d="M6 9l6 6 6-6" />
          </svg>
        </div>

        <!-- Dropdown -->
        <div
          class="absolute top-full left-0 mt-2 w-40 bg-white border border-black rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50"
        >
          <NuxtLink to="view" class="block px-4 py-2 hover:bg-green-100 hover:text-green-700">Shirts</NuxtLink>
          <NuxtLink to="view" class="block px-4 py-2 hover:bg-green-100 hover:text-green-700">Jackets</NuxtLink>
          <NuxtLink to="view" class="block px-4 py-2 hover:bg-green-100 hover:text-green-700">Pants</NuxtLink>
          <NuxtLink to="view" class="block px-4 py-2 hover:bg-green-100 hover:text-green-700">Shoes</NuxtLink>
        </div>
      </div>

      <!-- Womens -->
      <div class="relative group">
        <div class="flex items-center gap-1 hover:text-green-600 cursor-pointer">
          Womens
          <svg
            class="w-4 h-4 mt-1 transition-transform group-hover:rotate-180"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
          >
            <path d="M6 9l6 6 6-6" />
          </svg>
        </div>

        <!-- Dropdown -->
        <div
          class="absolute top-full left-0 mt-2 w-40 bg-white border border-black rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50"
        >
           <NuxtLink to="view" class="block px-4 py-2 hover:bg-green-100 hover:text-green-700">Shirts</NuxtLink>
          <NuxtLink to="view" class="block px-4 py-2 hover:bg-green-100 hover:text-green-700">Jackets</NuxtLink>
          <NuxtLink to="view" class="block px-4 py-2 hover:bg-green-100 hover:text-green-700">Pants</NuxtLink>
          <NuxtLink to="view" class="block px-4 py-2 hover:bg-green-100 hover:text-green-700">Shoes</NuxtLink>
        </div>
      </div>
    </nav>

    <!-- Auth Buttons -->
    <div class="flex space-x-6">
      <!-- Login -->
      <NuxtLink to="/login" class="text-black font-semibold hover:text-green-600">
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
  </header>
</template>

<script setup>
//add header logic here, like sorting and stuff
</script>
