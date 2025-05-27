<template>
  <main class="bg-white text-black">
    <!-- Hero Section -->
    <section class="bg-black text-white py-16 px-6 text-center rounded-b-3xl shadow-lg">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">Compare Clothing Prices Effortlessly</h1>
      <p class="text-lg md:text-xl text-gray-100 max-w-2xl mx-auto">
        Discover the best deals across top fashion stores. Stylish. Smart. Affordable.
      </p>
    </section>

    <!-- Featured Products -->
    <section ref="productsSection" class="py-16 bg-white">
      <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header + Sorting Row -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mt-12 mb-6 gap-4">
          <h2 class="text-3xl font-bold border-l-4 border-green-500 pl-4">
            Featured Products
          </h2>

<<<<<<< HEAD
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">
        <ProductCard v-for="product in products" :key="product.product_id" :product="product" />
      </div>
    </section>
=======
          <!-- Sort Dropdown -->
          <div class="inline-flex items-center space-x-2 border border-gray-300 rounded-lg px-4 py-2 shadow-sm bg-white">
            <label for="sort" class="text-sm font-medium">Sort by:</label>
            <select
              id="sort"
              v-model="sortOption"
              class="text-sm border-none focus:ring-0 focus:outline-none bg-transparent"
            >
              <option value="">Default</option>
              <option value="brand_asc">Brand A–Z</option>
              <option value="brand_desc">Brand Z–A</option>
              <option value="price_asc">Price Low → High</option>
              <option value="price_desc">Price High → Low</option>
            </select>
          </div>
        </div>
>>>>>>> a4f0881b12ac68059b05190fd6a9752031165bc4

        <!-- Loading / Empty States -->
        <div v-if="loading" class="text-center py-10 text-gray-700 text-lg">Loading products...</div>
        <div v-else-if="paginatedProducts.length === 0" class="text-center py-10 text-gray-500 text-lg">
          No products found.
        </div>

<<<<<<< HEAD
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-12">
        <ReviewCard v-for="(review, index) in reviews" :key="index" :name="review.name" :rating="review.rating"
          :comment="review.comment" />
      </div>

      <!-- CTA to Dashboard -->
      <div class="text-center">
        <NuxtLink to="/dashboard">
          <button
            class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-3 rounded-full shadow-lg transition duration-300">
            View Dashboard Analytics
=======
        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
          <ProductCard
            v-for="product in paginatedProducts"
            :key="product.product_id"
            :product="product"
          />
        </div>

        <!-- Pagination Controls -->
        <div class="flex justify-center items-center mt-12 space-x-6 text-sm text-gray-600">
          <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="hover:text-black transition disabled:opacity-30 disabled:cursor-not-allowed"
          >
            ← Previous
>>>>>>> a4f0881b12ac68059b05190fd6a9752031165bc4
          </button>
          <span class="text-gray-700 font-medium">
            Page {{ currentPage }} of {{ totalPages }}
          </span>
          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="hover:text-black transition disabled:opacity-30 disabled:cursor-not-allowed"
          >
            Next →
          </button>
        </div>

      </div>
    </section>
  </main>
</template>


<script setup>
import { ref, watch, computed, onMounted } from 'vue';
import ProductCard from '~/components/productcard.vue';
import { useProducts } from '~/composables/useProducts';

const loading = ref(true);
const rawProducts = ref([]);
const sortOption = ref('');
const priceRange = ref('');
const productsSection = ref(null);
const currentPage = ref(1);
const itemsPerPage = 8;

// Fetch once
const loadProducts = async () => {
  loading.value = true;
  rawProducts.value = await useProducts();
  loading.value = false;
};

<<<<<<< HEAD
loadProducts();


=======
// Filter by price
const filteredProducts = computed(() => {
  if (!priceRange.value) return rawProducts.value;

  return rawProducts.value.filter(product => {
    const prices = product.prices || [];
    if (prices.length === 0) return false;

    const minPrice = Math.min(...prices.map(p => parseFloat(p.price)));

    if (priceRange.value === '0-1500') return minPrice <= 1500;
    if (priceRange.value === '1500-2000') return minPrice > 1500 && minPrice <= 2000;
    if (priceRange.value === '2000+') return minPrice > 2000;

    return true;
  });
});

// Sort by brand or price
const sortedProducts = computed(() => {
  const sorted = [...filteredProducts.value];

  if (sortOption.value === 'brand_asc') {
    sorted.sort((a, b) => a.brand_name.localeCompare(b.brand_name));
  } else if (sortOption.value === 'brand_desc') {
    sorted.sort((a, b) => b.brand_name.localeCompare(a.brand_name));
  } else if (sortOption.value === 'price_asc') {
    sorted.sort((a, b) => {
      const priceA = Math.min(...(a.prices || []).map(p => p.price));
      const priceB = Math.min(...(b.prices || []).map(p => p.price));
      return priceA - priceB;
    });
  } else if (sortOption.value === 'price_desc') {
    sorted.sort((a, b) => {
      const priceA = Math.min(...(a.prices || []).map(p => p.price));
      const priceB = Math.min(...(b.prices || []).map(p => p.price));
      return priceB - priceA;
    });
  }

  return sorted;
});


// Pagination
const totalPages = computed(() =>
  Math.ceil(sortedProducts.value.length / itemsPerPage)
);

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return sortedProducts.value.slice(start, end);
});

// Scroll to top on page change
const scrollToProducts = () => {
  productsSection.value?.scrollIntoView({ behavior: 'smooth' });
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
    scrollToProducts();
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
    scrollToProducts();
  }
};

onMounted(() => {
  loadProducts();
});

// Reset to page 1 on filter/sort change
watch([sortOption, priceRange], () => {
  currentPage.value = 1;
});

>>>>>>> a4f0881b12ac68059b05190fd6a9752031165bc4
</script>