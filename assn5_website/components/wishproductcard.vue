<template>
  <div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition relative">
    <!-- Product Image with Remove Button -->
    <div class="relative">
      <img :src="image" :alt="name" class="w-full h-48 object-cover">
      
      <!-- Stylish Red X Button -->
      <button 
        @click.stop="$emit('remove-from-wishlist')"
        class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 w-8 h-8 rounded-full 
               flex items-center justify-center shadow-md transition-colors"
        aria-label="Remove from wishlist"
      >
        <span class="text-white font-bold text-lg">×</span>
      </button>
    </div>

    <!-- Product Info -->
    <div class="p-4">
      <h3 class="font-bold text-lg mb-2">{{ name }}</h3>
      <div class="flex items-center gap-2 mb-2">
        <span class="text-gray-500 line-through">R{{ originalPrice }}</span>
        <span class="font-bold text-green-600">R{{ discountedPrice }}</span>
      </div>
      <div class="flex flex-wrap gap-1">
        <span v-for="(store, index) in stores" :key="index" class="text-xs bg-gray-100 px-2 py-1 rounded">
          {{ store }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  image: String,
  name: String,
  originalPrice: Number,
  discountedPrice: Number,
  stores: Array
})

defineEmits(['remove-from-wishlist'])
</script>