<template>
  <div class="product-card">
    <NuxtLink :to="`/view?id=${product.product_id}`" class="block bg-white border border-gray-200 rounded-2xl shadow hover:shadow-lg transition p-4 w-full max-w-xs cursor-pointer">
      <!-- Product Image -->
      <img :src="product.images[0]" alt="Product Image" class="w-full h-60 object-cover rounded-xl mb-4" />

      <!-- Product Name -->
      <div class="text-black font-bold text-lg mb-1">{{ product.description }}</div>

      <!-- Brand -->
      <div class="text-sm text-gray-500 mb-2">by {{ product.brand_name }}</div>

      <!-- Prices -->
      <div v-if="lowestPrice" class="flex items-center space-x-2 mb-3">
        <span class="text-gray-500 line-through text-sm">
          R{{ originalPrice }}
        </span>
        <span class="text-green-600 font-semibold">
          R{{ lowestPrice }}
        </span>
      </div>

      <!-- Retailers -->
      <div class="space-y-2 text-sm">
        <div
          v-for="(price, index) in product.prices"
          :key="index"
          class="flex justify-between items-center bg-gray-50 px-3 py-1 rounded"
        >
          <span class="font-medium">{{ price.retailer_name }}</span>
          <span class="text-gray-800">R{{ Math.round(price.price / 100) * 100 }}</span>
          <span :class="price.availability > 0 ? 'text-green-600' : 'text-red-500'">
            {{ price.availability > 0 ? 'In Stock' : 'In Stock' }}
          </span>
        </div>
      </div>
    </NuxtLink>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
});

// Get lowest price from retailers
const lowestPrice = computed(() => {
  if (!props.product.prices || props.product.prices.length === 0) return null;
  const min = Math.min(...props.product.prices.map(p => p.price));
  return Math.round(min / 100) * 100;
});

const originalPrice = computed(() => {
  const randomIndex = Math.floor(Math.random() * props.product.prices.length);
  const price = props.product.prices[randomIndex]?.price + 500; // fake original price
  return Math.round(price / 100) * 100;
});

</script>

<style scoped>
/* You can keep existing styles or update as needed */
</style>