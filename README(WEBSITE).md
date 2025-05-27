# BARNEY-FRIENDS

A full-stack web application built as part of **COS 221 Assignment 5**, featuring:
- User login & signup
- Dynamic product listing with price comparison
- Product ratings and reviews
- Wishlist management
- Responsive design using Vue 3 + Tailwind CSS
- Proxy server (`server.js`) for secure access to university backend

---

## Features

| Feature | Description |

- Login / Signup | Secure session handling via `apikey` |
- Product Cards | Show product images, lowest prices, availability |
- Reviews | View and submit product reviews |
- Wishlist | Add/remove products from wishlist |
- Hamburger Menu | navigation drawer |
- Green/White Theme | Clean UI with hover effects and responsive layout |
- Express Proxy Server | Bypasses CORS issues when accessing Wheatley |

---

## Technologies Used

| **Nuxt 3** | Frontend framework |
| **Vue 3 Composition API** | Reactive logic |
| **Tailwind CSS** | Styling |
| **Express.js** | Proxy server for PHP API |
| **Axios** | HTTP requests from proxy |
| **localStorage** | Stores user session data (`apikey`, `user_id`) |
| **PHP API** | Backend hosted at [Wheatley](https://wheatley.cs.up.ac.za ) |
| **.env** | Environment variables for credentials |

---

## Folder Structure
BARNEY-FRIENDS/
├── assn5_website/ # Nuxt 3 frontend app
│ ├── components/ # ProductCard.vue, Header.vue, etc.
│ ├── composables/ # useApi.js, useAuth.js
│ ├── pages/ # index.vue, view.vue, dashboard.vue, wishlist.vue
│ ├── public/ # Static assets like images
│ └── nuxt.config.ts # Nuxt configuration
│
├── server.js # Express proxy server
├── utils.js # Axios config for proxy
├── .env # Wheatley credentials
└── package.json # Project dependencies

## Environment Variables
WHEATLEY_USER=your_uni_username
WHEATLEY_PASS=your_uni_password
NUXT_PUBLIC_API_BASE=http://localhost:3001/api

## Prerequisites

Before running the website, ensure you have:
- Node.js (v16 or v18 recommended)
- npm (comes with Node.js)
- A terminal / command line tool


## How to Install Dependencies

Navigate to the root folder in terminal:

bash
# Install required packages for Express proxy
cd assn5_website
npm install express cors dotenv axios
npm install

# Run project
cd assn5_website
node server.js
npm run dev