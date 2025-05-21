<template>
  <div class="bg-white shadow-md rounded-xl p-4 w-full max-w-md mx-auto">
    <h3 class="text-lg font-semibold text-gray-700 mb-4 text-center">Average Ratings</h3>
    <canvas ref="chartCanvas" class="w-full h-64"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import {
  Chart,
  BarController,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend,
  Title
} from 'chart.js'

// Register necessary components
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend, Title)

const chartCanvas = ref(null)

onMounted(() => {
  const ctx = chartCanvas.value.getContext('2d')

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['1★', '2★', '3★', '4★', '5★'],
      datasets: [
        {
          label: 'Number of Reviews',
          data: [2, 4, 6, 10, 14], // mock data
          backgroundColor: 'rgba(34,197,94,0.8)',
          borderRadius: 6,
          barThickness: 30,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          ticks: {
            color: '#4B5563', // Tailwind gray-600
            font: { size: 14 }
          },
          grid: { display: false }
        },
        y: {
          beginAtZero: true,
          ticks: {
            color: '#4B5563',
            font: { size: 14 },
            precision: 0
          },
          grid: { color: '#E5E7EB' } // Tailwind gray-200
        }
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#111827',
          titleColor: '#fff',
          bodyColor: '#fff',
          borderColor: '#6EE7B7',
          borderWidth: 1
        }
      }
    }
  })
})
</script>

<style scoped>
canvas {
  max-height: 240px;
}
</style>
