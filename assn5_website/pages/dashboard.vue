<template>
  <div class="p-8 bg-white min-h-screen">
    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-black mb-8">Dashboard</h1>

    <!-- Top Rated Products Section -->
    <section class="mb-16">
      <h2 class="text-2xl font-semibold mb-6 text-black">Top Rated Products</h2>

      <div v-if="loading" class="text-center py-10 text-gray-500">
        Loading top-rated products...
      </div>

      <div v-else-if="error" class="text-center py-10 text-red-500">
        {{ error }}
      </div>

      <div v-else-if="topProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <ProductCard
          v-for="product in topProducts"
          :key="product.product_id"
          :product="product"
        />
      </div>

      <div v-else class="text-center py-10 text-gray-500">
        No products found.
      </div>
    </section>

    <!-- Review Chart (Optional) -->
    <section v-if="ratingCounts.length > 0">
      <h2 class="text-2xl font-semibold mb-6 text-black">Review Summary</h2>
      <ReviewChart :ratings="ratingCounts" />
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ProductCard from '~/components/productcard.vue';
import ReviewChart from '~/components/reviewchart.vue'; // Optional
import { useApi } from '~/composables/useApi';

const topProducts = ref([]);
const loading = ref(true);
const error = ref(null);
const ratingCounts = ref([]);

// Fetch top-rated products
async function fetchTopProducts() {
  try {
    const result = await useApi({
      type: 'GetProducts' // Make sure this matches what your backend expects
    });

    if (result.status === 'success' && result.data?.products?.length > 0) {
      topProducts.value = result.data.products;
      error.value = null;
    } else {
      error.value = result.message || 'No products available';
      topProducts.value = [];
    }
  } catch (err) {
    console.error('Failed to load products:', err.message);
    error.value = 'Could not load products. Please try again later.';
    topProducts.value = [];
  } finally {
    loading.value = false;
  }
}

onMounted(fetchTopProducts);
</script>

<style scoped>
/* Add custom styles here if needed */
</style>