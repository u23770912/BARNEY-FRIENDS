<template>
  <NuxtLink to="/view" class="block bg-white border border-gray-200 rounded-2xl shadow hover:shadow-lg transition p-4 w-full max-w-xs cursor-pointer">
    <img :src="image" alt="Product Image" class="w-full h-60 object-cover rounded-xl mb-4" />

    <div class="text-black font-bold text-lg mb-1">{{ name }}</div>

    <div class="flex items-center space-x-2 mb-3">
      <span class="text-gray-500 line-through text-sm">R{{ originalPrice }}</span>
      <span class="text-green-600 font-semibold">R{{ discountedPrice }}</span>
    </div>

    <div class="text-sm text-gray-600 mb-1">Available at:</div>
    <ul class="text-sm text-black list-disc list-inside">
      <li v-for="(store, index) in stores" :key="index">{{ store }}</li>
    </ul>
  </NuxtLink>
</template>

<script setup>
defineProps({
  image: String,
  name: String,
  originalPrice: Number,
  discountedPrice: Number,
  stores: Array
})
</script>
