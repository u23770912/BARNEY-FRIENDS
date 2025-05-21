// utils/api.js
export const useApi = () => {
  const config = useRuntimeConfig()
  const baseURL = config.public.apiBase || 'https://wheatley.cs.up.ac.za/u23537036/apiB.php'

  const callApi = async (payload) => {
    try {
      const response = await $fetch(baseURL, {
        method: 'POST',
        body: payload,
        headers: {
          'Content-Type': 'application/json'
        }
      })
      return response
    } catch (error) {
      console.error('API Error:', error)
      throw error
    }
  }

  return {
    // Wishlist methods
    getWishlist: async (userId, apiKey) => {
      return callApi({
        type: 'GetWishlist',
        user_id: userId,
        apikey: apiKey
      })
    },
    addToWishlist: async (userId, productId, apiKey) => {
      return callApi({
        type: 'AddToWishlist',
        user_id: userId,
        product_id: productId,
        apikey: apiKey
      })
    },
    removeFromWishlist: async (userId, productId, apiKey) => {
      return callApi({
        type: 'RemoveFromWishlist',
        user_id: userId,
        product_id: productId,
        apikey: apiKey
      })
    }
  }
}