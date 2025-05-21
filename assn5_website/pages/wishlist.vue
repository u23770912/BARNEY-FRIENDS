<template>
  <div class="bg-white text-black">
    <!-- Hero Section -->
    <section class="bg-black text-white py-10 px-6 text-center rounded-b-3xl shadow-lg">
      <h1 class="text-4xl md:text-5xl font-bold mb-4">Your Wishlist</h1>
      <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto">
        Save your favorite items and track price drops across stores.
      </p>
    </section>

    <!-- Wishlist Items -->
    <section class="px-6 md:px-16 py-12">
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold border-l-4 border-green-500 pl-4">Saved Items</h2>
        <button 
          @click="clearWishlist"
          class="text-red-500 hover:text-red-700 font-medium"
          v-if="wishlist.length > 0"
        >
          Clear All
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-16">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500 mx-auto"></div>
        <p class="mt-4 text-gray-600">Loading your wishlist...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="wishlist.length === 0" class="text-center py-16">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <h3 class="text-xl font-medium mt-4">Your wishlist is empty</h3>
        <p class="text-gray-600 mt-2">Start adding items to your wishlist to see them here</p>
        <NuxtLink to="/" class="mt-6 inline-block bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-800 transition">
          Browse Products
        </NuxtLink>
      </div>

      <!-- Wishlist Products -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10" v-else>
        <WishProductCard
            v-for="(product, index) in wishlist"
            :key="product.product_id || index"
            :image="product.image || 'https://via.placeholder.com/300'"
            :name="product.name || 'Product Name'"
            :originalPrice="product.originalPrice || 0"
            :discountedPrice="product.discountedPrice || 0"
            :stores="product.stores || []"
            @remove-from-wishlist="removeFromWishlist(product.product_id)"
        />
      </div>
    </section>

    <!-- Price Drop Alerts -->
    <section class="bg-gray-50 px-6 md:px-16 py-12 rounded-t-3xl" v-if="wishlist.length > 0 && !loading">
      <h2 class="text-2xl font-bold mb-6 border-l-4 border-green-500 pl-4">Price Drop Alerts</h2>
      
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <input 
            type="checkbox" 
            id="priceAlerts" 
            v-model="priceAlertsEnabled"
            class="h-5 w-5 text-green-500 rounded focus:ring-green-400"
          >
          <label for="priceAlerts" class="ml-3 font-medium">Notify me when prices drop on my wishlist items</label>
        </div>
        <p class="text-gray-600 mt-3 text-sm">We'll send you an email when any item in your wishlist has a price reduction.</p>
      </div>
    </section>
  </div>
</template>

<script setup>
import WishProductCard from '~/components/wishproductcard.vue'

const config = useRuntimeConfig()
const wishlist = ref([])
const loading = ref(true)
const priceAlertsEnabled = ref(true)
const error = ref(null)

// Credentials - move these to .env in production!
const basicAuth = {
  username: 'u23537036',
  password: 'RamJas@*97'
}
const apiCredentials = {
  userId: 1, // Your API user ID
  apiKey: '6d7f8e9a0b1c2d3e4f5a6b7c8d9e0f1' // Your API key
}

onMounted(async () => {
  await fetchWishlist()
})

const fetchWishlist = async () => {
  try {
    loading.value = true
    error.value = null
    console.log('Attempting to fetch wishlist...')

    // First verify the endpoint is reachable
    try {
      const reachable = await checkEndpointReachable()
      if (!reachable) {
        throw new Error('API endpoint is not reachable')
      }
    } catch (reachError) {
      throw new Error(`Cannot connect to API: ${reachError.message}`)
    }

    // Create headers with Basic Auth
    const headers = new Headers()
    headers.append('Content-Type', 'application/json')
    headers.append('Authorization', `Basic ${btoa(`${basicAuth.username}:${basicAuth.password}`)}`)

    const response = await fetch('https://wheatley.cs.up.ac.za/u23537036/apiB.php', {
      method: 'POST',
      headers,
      body: JSON.stringify({
        type: 'GetWishlist',
        user_id: apiCredentials.userId,
        apikey: apiCredentials.apiKey
      }),
      credentials: 'include' // Important for CORS with credentials
    })

    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}))
      throw new Error(errorData.message || `HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    
    if (data.status === 'success') {
      wishlist.value = data.data.items.map(item => ({
        product_id: item.product_id,
        name: item.text || item.name || 'Product Name',
        description: item.description,
        image: item.image || 'https://via.placeholder.com/300',
        originalPrice: item.originalPrice || 0,
        discountedPrice: item.discountedPrice || 0,
        stores: item.stores || [],
        add_date: item.add_date
      }))
    } else {
      throw new Error(data.data?.message || 'API returned an error')
    }
  } catch (err) {
    console.error('Fetch error:', err)
    error.value = err.message
    // Show user-friendly error message
  } finally {
    loading.value = false
  }
}

const checkEndpointReachable = async () => {
  try {
    const response = await fetch('https://wheatley.cs.up.ac.za/u23537036/apiB.php', {
      method: 'OPTIONS',
      headers: {
        'Authorization': `Basic ${btoa(`${basicAuth.username}:${basicAuth.password}`)}`
      }
    })
    return response.ok
  } catch {
    return false
  }
}

// Add similar error handling to other methods
const removeFromWishlist = async (productId) => {
  try {
    const headers = new Headers()
    headers.append('Content-Type', 'application/json')
    headers.append('Authorization', `Basic ${btoa(`${basicAuth.username}:${basicAuth.password}`)}`)

    const response = await fetch('https://wheatley.cs.up.ac.za/u23537036/apiB.php', {
      method: 'POST',
      headers,
      body: JSON.stringify({
        type: 'RemoveFromWishlist',
        user_id: apiCredentials.userId,
        product_id: productId,
        apikey: apiCredentials.apiKey
      }),
      credentials: 'include'
    })

    if (!response.ok) throw new Error('Failed to remove item')
    
    wishlist.value = wishlist.value.filter(item => item.product_id !== productId)
  } catch (err) {
    console.error('Remove error:', err)
    error.value = err.message
  }
}
</script>