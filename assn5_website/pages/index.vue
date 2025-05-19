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
    <section class="px-6 md:px-16 py-12">
      <h2 class="text-3xl font-bold mb-8 border-l-4 border-green-500 pl-4">Featured Products</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">
        <ProductCard
          v-for="(product, index) in products"
          :key="index"
          :image="product.image"
          :name="product.name"
          :originalPrice="product.originalPrice"
          :discountedPrice="product.discountedPrice"
          :stores="product.stores"
        />
      </div>
    </section>

    <!-- Reviews Section -->
    <section class="bg-gray-50 px-6 md:px-16 py-12 rounded-t-3xl">
      <h2 class="text-2xl font-bold mb-6 border-l-4 border-green-500 pl-4">Customer Reviews</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <ReviewCard
          v-for="(review, index) in reviews"
          :key="index"
          :name="review.name"
          :rating="review.rating"
          :comment="review.comment"
        />
      </div>
    </section>
  </div>
</template>

<script setup>
import ProductCard from '~/components/productcard.vue'
import ReviewCard from '~/components/reviewcard.vue'

const products = [
  {
    name: "Nike x Drake NOCTA Puffer Jacket ‘Bright Yellow",
    image: 'https://sneakertwenty4.com/wp-content/uploads/2024/04/My-project-1-14-2-Recovered.jpg0_.jpg',
    originalPrice: 3000,
    discountedPrice: 2200,
    stores: ['Zara', 'Superbalist']
  },
  {
    name: "Jordan Women's 1 Low SE Sail/Seafoam Sneaker",
    image: 'https://thefoschini.vtexassets.com/arquivos/ids/183732328-1200-1600?v=638803054822000000&width=1200&height=1600&aspect=true',
    originalPrice: 2300,
    discountedPrice: 1800,
    stores: ['CourtOrder', 'Shesha']
  },
  // Add more sample products here
]

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
  },
  // Add more sample reviews if needed
]
</script>
