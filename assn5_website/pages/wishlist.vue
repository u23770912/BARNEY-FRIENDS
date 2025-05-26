<template>
  <div class="p-6 bg-white min-h-screen text-black">
    <!-- Page Title -->
    <h1 class="text-3xl font-extrabold mb-8 text-center">My Wishlist</h1>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-10 text-gray-500">Loading wishlist...</div>

    <!-- Error Message -->
    <div v-else-if="error" class="text-center py-10 text-red-500">{{ error }}</div>

    <!-- Empty Wishlist -->
    <div v-else-if="wishlist.length === 0" class="text-center py-10 text-gray-500">
      Your wishlist is empty.
    </div>

    <!-- Wishlist Table -->
    <div v-else class="max-w-6xl mx-auto overflow-x-auto">
      <table class="min-w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-white">
          <tr>
            <th class="px-6 py-4 text-left text-sm font-semibold">Product</th>
            <th class="px-6 py-4 text-left text-sm font-semibold">Added On</th>
            <th class="px-6 py-4 text-right text-sm font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in wishlist"
              :key="item.product_id"
              :class="[
                'border-t transition-colors',
                index % 2 === 0 ? 'bg-white' : 'bg-gray-50',
                { 'bg-green-50': index === 0 }
              ]"
              @mouseenter="hoveredIndex = index"
              @mouseleave="hoveredIndex = null"
          >
            <!-- Product Name -->
            <td class="px-6 py-4">
              <NuxtLink :to="`/view?id=${item.product_id}`" class="font-medium text-black hover:text-green-600 hover:underline transition">
                {{ item.description || 'Unknown Product' }}
              </NuxtLink>
            </td>

            <!-- Added On -->
            <td class="px-6 py-4 text-gray-700">
              {{ formatDate(item.add_date) }}
            </td>

            <!-- Actions -->
            <td class="px-6 py-4 text-right">
              <button @click="removeFromWishlist(item.product_id)"
                      class="text-red-600 hover:text-red-800 font-medium transition px-3 py-1 rounded hover:bg-red-100">
                Remove
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuth } from '~/composables/useAuth';
import { useApi } from '~/composables/useApi';

const wishlist = ref([]);
const loading = ref(true);
const error = ref(null);
const hoveredIndex = ref(null);

const { user, apiKey, isLoggedIn } = useAuth();

// Fetch wishlist items
async function fetchWishlist() {
  if (!isLoggedIn.value) {
    error.value = 'You must be logged in to view your wishlist.';
    loading.value = false;
    return;
  }

  try {
    const result = await useApi({
      type: 'GetWishlist',
      user_id: user.value?.id || 1,
      apikey: apiKey.value
    });

    if (result.status === 'success') {
      wishlist.value = result.data.items || [];
    } else {
      throw new Error(result.message || 'Failed to load wishlist');
    }
  } catch (err) {
    console.error('Failed to load wishlist:', err.message);
    error.value = 'Could not load wishlist. Please try again later.';
  } finally {
    loading.value = false;
  }
}

// Format date helper
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

// Remove product from wishlist
async function removeFromWishlist(productId) {
  try {
    const result = await useApi({
      type: 'RemoveFromWishlist',
      user_id: user.value?.id || 1,
      product_id: productId,
      apikey: apiKey.value
    });

    if (result.status === 'success') {
      wishlist.value = wishlist.value.filter(item => item.product_id !== productId);
    } else {
      throw new Error(result.message || 'Could not remove product');
    }
  } catch (err) {
    console.error('Wishlist removal failed:', err.message);
    alert(`Could not remove product: ${err.message}`);
  }
}

onMounted(fetchWishlist);
</script>

<style scoped>
/* Optional: Add custom styles if needed */
</style>