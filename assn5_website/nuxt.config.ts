import tailwindcss from "@tailwindcss/vite";

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-05-15',
  devtools: { enabled: true },
  css: ['~/assets/css/main.css'],
  vite: {
    plugins: [
      tailwindcss(),
    ]
  },
  runtimeConfig: {
    public: {
      apiBase: 'https://wheatley.cs.up.ac.za/u23537036',
      apiUser: 'u23537036',
      apiPass: 'RamJas@*97', // Move to .env in production!
      apiKey: '6d7f8e9a0b1c2d3e4f5a6b7c8d9e0f1' // Move to .env!
    }
  },
  ssr: true,
  // Force HTTPS in development
  devServer: {
    https: true
  }
})
