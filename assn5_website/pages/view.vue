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
              :key="item.store"
              class="border-t hover:bg-gray-50"
            >
              <td class="py-2 px-3">{{ item.store }}</td>
              <td class="py-2 px-3 font-semibold">{{ item.price }}</td>
              <td class="py-2 px-3">
                <NuxtLink
                  :to="item.link"
                  target="_blank"
                  class="text-green-600 hover:underline"
                  >Visit&nbsp;Store</NuxtLink
                >
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
import { computed } from 'vue';
import { useRoute } from 'vue-router';

// --- sample data (match index.vue) ------------------------------------
const sampleProducts = [
  {
    id: 0,
    name: 'Nike x Drake NOCTA Puffer Jacket “Bright Yellow"',
    image:
      'https://sneakertwenty4.com/wp-content/uploads/2024/04/My-project-1-14-2-Recovered.jpg0_.jpg',
    prices: [
      { store: 'Zara', price: 3000, link: '#' },
      { store: 'Superbalist', price: 2200, link: '#' }
    ],
    reviews: [
      { name: 'Thabo M.', rating: 5, comment: 'Incredible quality!' },
      { name: 'Aisha P.', rating: 4, comment: 'Love the colour.' }
    ]
  },
  {
    id: 1,
    name: "Jordan Women's 1 Low SE Sail/Seafoam Sneaker",
    image:
      'https://thefoschini.vtexassets.com/arquivos/ids/183732328-1200-1600?v=638803054822000000&width=1200&height=1600&aspect=true',
    prices: [
      { store: 'CourtOrder', price: 2300, link: '#' },
      { store: 'Shesha', price: 1800, link: '#' }
    ],
    reviews: [
      { name: 'Liam J.', rating: 3, comment: 'Shipping was slow.' }
    ]
  }
];
// ----------------------------------------------------------------------

const route = useRoute();
const id = Number(route.params.id || 0);

const product = computed(() => sampleProducts.find((p) => p.id === id) || sampleProducts[0]);

// dummy wishlist toggle
import { ref } from 'vue';
const isWishlisted = ref(false);

function toggleWishlist() {
  isWishlisted.value = !isWishlisted.value;
}
</script>

<style scoped>
.product-view table th,
.product-view table td {
  border: 1px solid rgba(0, 0, 0, 0.1);
}
</style>
