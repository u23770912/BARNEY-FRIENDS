<template>
  <div class="bg-white border border-gray-200 rounded-2xl shadow p-4 w-full max-w-md">
    <div class="flex items-center justify-between mb-2">
      <h3 class="text-lg font-semibold text-black">{{ name }}</h3>
      <div class="flex items-center space-x-1">
        <span
          v-for="n in 5"
          :key="n"
          class="text-yellow-400"
        >
          <i :class="n <= rating ? 'fas fa-star' : 'far fa-star'"></i>
        </span>
      </div>
    </div>
    <p class="text-gray-700 text-sm">
      {{ shortComment }}
    </p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  name: String,
  rating: Number,
  comment: String
})

const shortComment = computed(() =>
  props.comment && props.comment.length > 100
    ? props.comment.slice(0, 100) + '...'
    : props.comment || ''
)

</script>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
</style>
