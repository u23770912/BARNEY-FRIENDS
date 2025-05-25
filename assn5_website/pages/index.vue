<template>
  <div class="bg-white text-black">
    <!-- Hero Section -->
    <section class="bg-black text-white py-10 px-6 text-center rounded-b-3xl shadow-lg">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">Compare Clothing Prices Effortlessly</h1>
      <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto">
        Discover the best deals across top fashion stores. Stylish. Smart. Affordable.
      </p>
    </section>

    <!-- Featured Products -->
    <section class="px-6 md:px-16 py-16">
      <h2 class="text-3xl font-bold mb-10 border-l-4 border-green-500 pl-4">Featured Products</h2>

      <div v-if="loading" class="text-center py-10">Loading products...</div>
      <div v-else-if="products.length === 0 && !loading" class="text-center py-10 text-gray-500">
        No products found.
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">
        <ProductCard
          v-for="product in products"
          :key="product.product_id"
          :product="product"
        />
      </div>
    </section>

    <!-- Reviews Section -->
    <section class="bg-gray-50 px-6 md:px-16 py-16 rounded-t-3xl">
      <h2 class="text-2xl font-bold mb-8 border-l-4 border-green-500 pl-4">Customer Reviews</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-12">
        <ReviewCard
          v-for="(review, index) in reviews"
          :key="index"
          :name="review.name"
          :rating="review.rating"
          :comment="review.comment"
        />
      </div>

      <!-- CTA to Dashboard -->
      <div class="text-center">
        <NuxtLink to="/dashboard">
          <button class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-full shadow-lg transition duration-300">
            View Dashboard Analytics
          </button>
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup>
import ProductCard from '~/components/productcard.vue';
import ReviewCard from '~/components/reviewcard.vue';
import { useProducts } from '~/composables/useProducts';

const loading = ref(true);
const products = ref([]);
const reviews = [
  {
    name: 'Thabo M.',
    rating: 5,
    comment: 'Excellent quality and delivery time was super quick. Will definitely buy again!'
  },
  {
    name: 'Aisha P.',
    rating: 4,
    comment: 'Good product overall, but size ran a bit small. Still happy though.'
  },
  {
    name: 'Liam J.',
    rating: 3,
    comment: 'Product was okay, but shipping took a while and packaging was a bit damaged.'
  }
];

// Fetch real products
const loadProducts = async () => {
  products.value = await useProducts();
  loading.value = false;
};

loadProducts();
</script>