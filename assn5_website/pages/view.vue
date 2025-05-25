<template>
  <div class="product-view py-10 px-6 md:px-16 max-w-6xl mx-auto">
    <div v-if="!product" class="text-center py-10">
      Loading product...
    </div>
    
    <div v-else class="flex flex-col lg:flex-row gap-10">
      <!-- Product image -->
      <img
        :src="productImage"
        :alt="product.description || 'Product Image'"
        class="w-full lg:w-1/2 rounded-xl shadow-lg object-cover"
      />

      <!-- Product details -->
      <div class="flex-1">
        <div class="flex items-start justify-between gap-4 mb-4">
          <h1 class="text-3xl font-bold leading-tight">{{ product.description || 'Product' }}</h1>

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

        <!-- Availability Status -->
        <div class="mb-6">
          <span :class="product.availability === '1' ? 'text-green-600' : 'text-red-500'" class="font-semibold">
            {{ product.availability === '1' ? 'In Stock' : 'Out of Stock' }}
          </span>
        </div>

        <!-- Price Comparison Section -->
        <div class="mt-6">
          <h2 class="text-xl font-semibold mb-4">Compare Prices</h2>
          
          <!-- Loading state for prices -->
          <div v-if="pricesLoading" class="text-center py-6 text-gray-500">
            Loading prices...
          </div>
          
          <!-- No prices state -->
          <div v-else-if="prices.length === 0" class="bg-gray-50 p-6 rounded-lg text-center text-gray-500">
            <p>Price information not available</p>
          </div>
          
          <!-- Prices table -->
          <div v-else class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <div class="grid grid-cols-4 bg-gray-50 font-semibold text-gray-700 text-sm">
              <div class="px-4 py-3 border-r">Store</div>
              <div class="px-4 py-3 border-r">Price</div>
              <div class="px-4 py-3 border-r">Stock</div>
              <div class="px-4 py-3">Action</div>
            </div>
            
            <div
              v-for="(price, index) in sortedPrices"
              :key="price.retailer_id"
              :class="[
                'grid grid-cols-4 border-t hover:bg-gray-50 transition-colors',
                index === 0 ? 'bg-green-50 border-green-200' : ''
              ]"
            >
              <!-- Store name with logo placeholder -->
              <div class="px-4 py-4 flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                  {{ getStoreInitials(price.retailer_name) }}
                </div>
                <div>
                  <div class="font-medium text-gray-900">{{ price.retailer_name }}</div>
                  <div v-if="index === 0" class="text-xs text-green-600 font-medium">Best Price!</div>
                </div>
              </div>
              
              <!-- Price -->
              <div class="px-4 py-4 flex items-center">
                <div>
                  <div :class="[
                    'text-lg font-bold',
                    index === 0 ? 'text-green-600' : 'text-gray-900'
                  ]">
                    R{{ formatPrice(price.price) }}
                  </div>
                  <div v-if="index > 0 && prices.length > 1" class="text-xs text-gray-500">
                    +R{{ (price.price - sortedPrices[0].price).toFixed(2) }} more
                  </div>
                </div>
              </div>
              
              <!-- Stock status -->
              <div class="px-4 py-4 flex items-center">
                <span :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  price.availability > 0 
                    ? 'bg-green-100 text-green-800' 
                    : 'bg-red-100 text-red-800'
                ]">
                  <div :class="[
                    'w-1.5 h-1.5 rounded-full mr-1',
                    price.availability > 0 ? 'bg-green-400' : 'bg-red-400'
                  ]"></div>
                  {{ price.availability > 0 ? `${price.availability} in stock` : 'Out of stock' }}
                </span>
              </div>
              
              <!-- Action button -->
              <div class="px-4 py-4 flex items-center">
                <a
                  :href="getStoreLink(price.retailer_name, price.retailer_id)"
                  target="_blank"
                  rel="noopener noreferrer"
                  :class="[
                    'inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-all',
                    price.availability > 0
                      ? index === 0
                        ? 'bg-green-600 hover:bg-green-700 text-white shadow-sm'
                        : 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm'
                      : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                  ]"
                  :aria-disabled="price.availability <= 0"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                  </svg>
                  {{ price.availability > 0 ? 'View at Store' : 'Unavailable' }}
                </a>
              </div>
            </div>
          </div>
          
          <!-- Price insights -->
          <div v-if="prices.length > 1" class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <div>
                <h3 class="font-medium text-blue-900 mb-1">Price Comparison Insights</h3>
                <p class="text-sm text-blue-700">
                  You can save <strong>R{{ (sortedPrices[sortedPrices.length - 1].price - sortedPrices[0].price).toFixed(2) }}</strong> 
                  by choosing {{ sortedPrices[0].retailer_name }} over {{ sortedPrices[sortedPrices.length - 1].retailer_name }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Product Information -->
        <div class="mt-8">
          <h2 class="text-xl font-semibold mb-3">Product Information</h2>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p><strong>Product ID:</strong> {{ product.product_id }}</p>
            <p><strong>Brand ID:</strong> {{ product.brand_id }}</p>
            <p><strong>Retailer ID:</strong> {{ product.retailer_id }}</p>
          </div>
        </div>

        <!-- Additional Images -->
        <div v-if="images && images.length > 0" class="mt-8">
          <h2 class="text-xl font-semibold mb-3">Additional Images</h2>
          <div class="grid grid-cols-3 gap-4">
            <img
              v-if="images[0]?.url_1"
              :src="images[0].url_1"
              :alt="product.description"
              class="w-full h-24 object-cover rounded-lg"
            />
            <img
              v-if="images[0]?.url_2"
              :src="images[0].url_2"
              :alt="product.description"
              class="w-full h-24 object-cover rounded-lg"
            />
            <img
              v-if="images[0]?.url_3"
              :src="images[0].url_3"
              :alt="product.description"
              class="w-full h-24 object-cover rounded-lg"
            />
          </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-10">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold">Customer Reviews</h2>
            <div v-if="reviews.length > 0" class="text-sm text-gray-600">
              {{ reviews.length }} review{{ reviews.length !== 1 ? 's' : '' }}
            </div>
          </div>

          <!-- Loading state for reviews -->
          <div v-if="reviewsLoading" class="text-center py-6 text-gray-500">
            Loading reviews...
          </div>

          <!-- No reviews state -->
          <div v-else-if="reviews.length === 0" class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
            <p class="text-lg mb-2">No reviews yet</p>
            <p class="text-sm">Be the first to review this product!</p>
          </div>

          <!-- Reviews list -->
          <div v-else class="space-y-6">
            <div
              v-for="review in reviews"
              :key="review.review_id"
              class="border-b border-gray-200 pb-6 last:border-b-0"
            >
              <!-- Review header -->
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">
                    {{ (review.name.charAt(0) + review.surname.charAt(0)).toUpperCase() }}
                  </div>
                  <div>
                    <p class="font-semibold text-gray-900">
                      {{ review.name }} {{ review.surname }}
                    </p>
                    <p class="text-sm text-gray-500">
                      {{ formatDate(review.review_date) }}
                    </p>
                  </div>
                </div>
                
                <!-- Star rating -->
                <div class="flex items-center gap-1">
                  <div class="flex">
                    <svg
                      v-for="star in 5"
                      :key="star"
                      :class="star <= parseInt(review.rating) ? 'text-yellow-400' : 'text-gray-300'"
                      class="w-4 h-4 fill-current"
                      viewBox="0 0 20 20"
                    >
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                  </div>
                  <span class="text-sm text-gray-600 ml-1">{{ review.rating }}/5</span>
                </div>
              </div>

              <!-- Review text -->
              <p class="text-gray-700 leading-relaxed">{{ review.text }}</p>
            </div>
          </div>

          <!-- Average rating summary -->
          <div v-if="reviews.length > 0" class="mt-8 p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center gap-4">
              <div class="text-center">
                <div class="text-2xl font-bold text-gray-900">{{ averageRating }}</div>
                <div class="flex justify-center mt-1">
                  <svg
                    v-for="star in 5"
                    :key="star"
                    :class="star <= Math.round(averageRating) ? 'text-yellow-400' : 'text-gray-300'"
                    class="w-4 h-4 fill-current"
                    viewBox="0 0 20 20"
                  >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                </div>
                <div class="text-sm text-gray-500 mt-1">Average Rating</div>
              </div>
              <div class="flex-1">
                <div v-for="rating in [5, 4, 3, 2, 1]" :key="rating" class="flex items-center gap-2 mb-1">
                  <span class="text-sm w-3">{{ rating }}</span>
                  <svg class="w-3 h-3 text-yellow-400 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                  <div class="flex-1 bg-gray-200 rounded-full h-2">
                    <div 
                      class="bg-yellow-400 h-2 rounded-full" 
                      :style="{ width: ratingPercentage(rating) + '%' }"
                    ></div>
                  </div>
                  <span class="text-sm text-gray-500 w-8">{{ ratingCount(rating) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  import { useApi } from '~/composables/useApi';

  const route = useRoute();
  const id = Number(route.query.id || 0);
  const product = ref(null);
  const images = ref([]);
  const reviews = ref([]);
  const prices = ref([]);
  const reviewsLoading = ref(false);
  const pricesLoading = ref(false);
  const isWishlisted = ref(false);

  // Computed property to get the main product image
  const productImage = computed(() => {
    if (images.value && images.value.length > 0 && images.value[0]?.url_1) {
      return images.value[0].url_1;
    }
    return 'https://via.placeholder.com/400x400?text=No+Image'; // Fallback image
  });

  // Computed property for sorted prices (lowest first)
  const sortedPrices = computed(() => {
    return [...prices.value].sort((a, b) => parseFloat(a.price) - parseFloat(b.price));
  });
  const averageRating = computed(() => {
    if (reviews.value.length === 0) return 0;
    const total = reviews.value.reduce((sum, review) => sum + parseInt(review.rating), 0);
    return (total / reviews.value.length).toFixed(1);
  });

  // Helper functions for rating breakdown
  const ratingCount = (rating) => {
    return reviews.value.filter(review => parseInt(review.rating) === rating).length;
  };

  const ratingPercentage = (rating) => {
    if (reviews.value.length === 0) return 0;
    return (ratingCount(rating) / reviews.value.length) * 100;
  };

  // Format date helper
  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  // Fetch product via local proxy
  async function fetchProduct() {
    if (!id) return;

    try {
      const result = await useApi({
        type: 'GetProduct',
        product_id: id
      });

      if (result.status === 'success') {
        // Handle the actual API response structure
        product.value = result.product;
        images.value = result.images || [];
      } else {
        console.error('Failed to load product:', result.message);
      }
    } catch (err) {
      console.error('API error:', err.message);
    }
  }

  // Fetch reviews for the product
  async function fetchReviews() {
    if (!id) return;

    reviewsLoading.value = true;
    try {
      const result = await useApi({
        type: 'GetReviews',
        product_id: id.toString()
      });

      if (result.status === 'success') {
        reviews.value = result.reviews || [];
      } else {
        console.error('Failed to load reviews:', result.message);
        reviews.value = [];
      }
    } catch (err) {
      console.error('Reviews API error:', err.message);
      reviews.value = [];
    } finally {
      reviewsLoading.value = false;
    }
  }

  // Fetch prices for the product
  async function fetchPrices() {
    if (!id) return;

    pricesLoading.value = true;
    try {
      const result = await useApi({
        type: 'GetPrices',
        product_id: id.toString()
      });

      if (result.status === 'success') {
        prices.value = result.prices || [];
      } else {
        console.error('Failed to load prices:', result.message);
        prices.value = [];
      }
    } catch (err) {
      console.error('Prices API error:', err.message);
      prices.value = [];
    } finally {
      pricesLoading.value = false;
    }
  }

  function toggleWishlist() {
    isWishlisted.value = !isWishlisted.value;
  }

  onMounted(async () => {
    await fetchProduct();
    await fetchReviews();
    await fetchPrices();
  });
</script>

<style scoped>
.product-view table th,
.product-view table td {
  border: 1px solid rgba(0, 0, 0, 0.1);
}
</style>