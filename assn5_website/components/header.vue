<template>
  <header class="bg-white border-b border-black px-8 py-4 flex items-center justify-between">
    <!-- Logo -->
    <!-- Logo (clickable → index page) -->
    <NuxtLink
      to="/"
      class="text-3xl font-extrabold text-black select-none cursor-pointer hover:text-green-600"
    >
      CompareIT
    </NuxtLink>

    <!-- Auth Buttons -->
    <div class="flex space-x-6">
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
  </header>
</template>

<script setup>
//add header logic here, like sorting and stuff
</script>
