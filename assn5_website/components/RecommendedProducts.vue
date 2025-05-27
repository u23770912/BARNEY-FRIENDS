<template>
  <div class="recommended-products p-4">
    <h2 class="text-xl font-bold mb-4">Recommended for You</h2>

    <div v-if="loading" class="text-gray-500">Loading recommendations...</div>
    <div v-else-if="error" class="text-red-500">{{ error }}</div>
    <div v-else-if="products.length === 0" class="text-gray-500">No recommendations available.</div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
      <NuxtLink to:="view"
        v-for="product in products"
        :key="product.product_id"
        :to="`/view?product_id=${product.product_id}`"
        class="block border rounded-xl p-4 shadow hover:shadow-lg transition"
      >
        <h3 class="font-semibold text-lg mb-2">{{ product.description }}</h3>
        <p><strong>Brand:</strong> {{ product.brand_id }}</p>
        <p><strong>Retailer:</strong> {{ product.retailer_id }}</p>
        <p>
          <strong>Avg Rating:</strong>
          <span v-if="product.avg_rating">{{ parseFloat(product.avg_rating).toFixed(1) }}</span>
          <span v-else class="italic text-gray-400">No ratings</span>
        </p>
      </NuxtLink>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRecommendations } from '~/composables/useRecommendations';

const products = ref([]);

onMounted(async () => {
  products.value = await useRecommendations();
});
</script>


<style scoped>
.recommended-products {
  background: #fff;
  border-radius: 1rem;
}
</style>
