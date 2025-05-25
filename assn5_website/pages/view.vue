<template>
  <div class="product-view py-10 px-6 md:px-16 max-w-6xl mx-auto">
    <div class="flex flex-col lg:flex-row gap-10">
      <!-- Product image -->
      <img
        :src="product.image"
        :alt="product.name"
        class="w-full lg:w-1/2 rounded-xl shadow-lg object-cover"
      />

      <!-- Product details -->
      <div class="flex-1">
        <div class="flex items-start justify-between gap-4 mb-4">
          <h1 class="text-3xl font-bold leading-tight">{{ product.name }}</h1>

          <!-- Wishlist heart -->
          <button
            @click="toggleWishlist"
            class="text-red-500 hover:text-red-600 transition-colors"
            aria-label="Add to wishlist"
          >
            <svg
              v-if="isWishlisted"
              xmlns="http://www.w3.org/2000/svg"
              fill="currentColor"
              viewBox="0 0 24 24"
              class="w-8 h-8"
            >
              <path
                d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42
                   3 7.5 3c1.74 0 3.41 0.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3
                   19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
              />
            </svg>
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
              class="w-8 h-8"
            >
              <path
                d="M12.76 3.62l.24.26.24-.26C14.55 2.41 16.45 2 18.24 2 21.47 2
                  24 4.56 24 8c0 3.87-3.4 7.06-9.55 11.73L12 22l-2.45-2.27C3.4
                  15.06 0 11.87 0 8 0 4.56 2.53 2 5.76 2c1.79 0 3.69.41 5 1.62z"
              />
            </svg>
          </button>
        </div>

        <!-- Price comparison table -->
        <h2 class="text-xl font-semibold mb-3">Prices at different retailers</h2>
        <table class="w-full text-left border border-collapse border-black/10">
          <thead>
            <tr class="bg-gray-100">
              <th class="py-2 px-3 font-medium">Retailer</th>
              <th class="py-2 px-3 font-medium">Price (R)</th>
              <th class="py-2 px-3 font-medium"></th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in product.prices"
              :key="item.retailer_name"
              class="border-t hover:bg-gray-50"
            >
              <td class="py-2 px-3">{{ item.retailer_name }}</td>
              <td class="py-2 px-3 font-semibold">R{{ Math.round(item.price / 100) * 100 }}</td>
              <td class="py-2 px-3">
                <span :class="item.availability > 0 ? 'text-green-600' : 'text-red-500'">
                  {{ item.availability > 0 ? 'In Stock' : 'Out of Stock' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Reviews -->
        <div v-if="product.reviews?.length" class="mt-10">
          <h2 class="text-xl font-semibold mb-4">Ratings & Reviews</h2>
          <div
            v-for="review in product.reviews"
            :key="review.name"
            class="border-b py-4"
          >
            <p class="font-semibold">
              {{ review.name }} — {{ review.rating }} / 5
            </p>
            <p class="text-sm text-gray-700">{{ review.comment }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  import { useApi } from '~/composables/useApi';

  const route = useRoute();
  const id = Number(route.query.id || 0);
  const product = ref(null);
  const isWishlisted = ref(false);

  // Fetch product via local proxy
  async function fetchProduct() {
    if (!id) return;

    try {
      const result = await useApi({
        type: 'GetProduct',
        id
      });

      if (result.status === 'success' && result.data?.product) {
        product.value = result.data.product;
      } else {
        console.error('Failed to load product:', result.message);
      }
    } catch (err) {
      console.error('API error:', err.message);
    }
  }

  function toggleWishlist() {
    isWishlisted.value = !isWishlisted.value;
  }

  onMounted(fetchProduct);
</script>


<style scoped>
.product-view table th,
.product-view table td {
  border: 1px solid rgba(0, 0, 0, 0.1);
}
</style>
