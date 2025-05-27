<template>
  <div class="p-8 bg-white min-h-screen">
    <!-- Page Title -->
    <h1 class="text-3xl font-bold text-black mb-8">Top Rated Products</h1>

    <!-- Top Rated Products Section -->
    <section class="mb-16">

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
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ProductCard from '~/components/productcard.vue';
import { useProducts } from '~/composables/useProducts';

const topProducts = ref([]);
const loading = ref(true);
const error = ref(null);
const ratingCounts = ref([]);

const config = useRuntimeConfig();
const apiUrl = config.public.apiBase;

// Helper: Get reviews for a product
const fetchReviews = async (productId) => {
  try {
    const res = await fetch(apiUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ type: 'GetReviews', product_id: productId })
    });

    if (!res.ok) throw new Error('Failed to fetch reviews');

    const data = await res.json();
    return data.status === 'success' ? data.reviews : [];
  } catch (err) {
    console.error(`Error fetching reviews for product ${productId}:`, err.message);
    return [];
  }
};

// Main logic
const loadProducts = async () => {
  loading.value = true;
  try {
    const allProducts = await useProducts(); // Fetch all products

    const enriched = await Promise.all(
      allProducts.map(async (product) => {
        const reviews = await fetchReviews(product.product_id);

        if (reviews.length === 0) return null;

        const avgRating =
          reviews.reduce((sum, r) => sum + parseFloat(r.rating), 0) / reviews.length;

        product.reviews = reviews;
        product.averageRating = avgRating;

        return avgRating >= 4 ? product : null;
      })
    );

    // Filter out nulls and sort by rating (optional)
    topProducts.value = enriched.filter(Boolean).sort(
      (a, b) => b.averageRating - a.averageRating
    );

    // Optional: build rating histogram
    const allRatings = topProducts.value.flatMap(p => p.reviews.map(r => r.rating));
    const counts = [0, 0, 0, 0, 0]; // index 0 -> 1 star, index 4 -> 5 stars
    allRatings.forEach(r => {
      const index = Math.round(r) - 1;
      if (index >= 0 && index < 5) counts[index]++;
    });
    ratingCounts.value = counts;
  } catch (err) {
    error.value = 'Failed to load top-rated products.';
    console.error(err.message);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadProducts();
});
</script>


<style scoped>
/* Add custom styles here if needed */
</style>