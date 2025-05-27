<template>
  <div class="login-page">
    <div class="login-card">
      <h2 class="card-title">Sign&nbsp;in to <span class="brand">CompareIT</span></h2>

      <form class="login-form" @submit.prevent="onSubmit">
        <div class="form-field">
          <label for="email">Email</label>
          <input
            type="email"
            id="email"
            v-model="email"
            placeholder="tonystark@gmail.com"
            required
          />
        </div>

        <div class="form-field relative">
          <label for="password" class="block mb-1 font-medium text-gray-700">Password</label>
          <input
            :type="showPassword ? 'text' : 'password'"
            id="password"
            v-model="password"
            placeholder="••••••••"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm pr-10 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
          />

          <!-- Show/hide button inside input -->
          <button
            type="button"
            @click="showPassword = !showPassword"
            class="absolute right-3 top-[38px] text-sm text-gray-500 hover:text-gray-800 focus:outline-none"
            aria-label="Toggle password visibility"
          >
            {{ showPassword ? 'Hide' : 'Show' }}
          </button>
        </div>


        <button type="submit" class="btn-submit">Login</button>
        
        <p class="register-link">
          Don't have an account?
          <router-link to="/signup" class="link">Register here</router-link>
        </p>

        <!-- feedback messages -->
        <p v-if="error" class="error">{{ error }}</p>
        <p v-if="success" class="success">{{ success }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import { useApi } from '~/composables/useApi';
  import { useAuth } from '~/composables/useAuth';

  const email = ref('');
  const password = ref('');
  const error = ref('');
  const success = ref('');
  const router = useRouter();
  const showPassword = ref(false);

  const { isLoggedIn , refreshAuth } = useAuth();

  async function onSubmit() {
  error.value = '';
  success.value = '';

  try {
    const data = await useApi({
      type: 'Login',
      email: email.value,
      password: password.value,
    });

    console.log('API Response:', data);

    if (data.status === 'success' && data.data?.apikey) {

      const user = {
        id:data.data.userid,
        name: data.data.name,
        apikey: data.data.apikey
      };
     
      localStorage.setItem('user', JSON.stringify(user));

      refreshAuth();

      success.value = 'Login successful! Redirecting...';
      setTimeout(() => {
        router.push('/');
      }, 1000);
    } else {
      error.value = 'Incorrect email or password';
    }

  } catch (err) {
    if(err?.response?.data?.message){
      error.value = err.response.data.message;
    }
    else if(err?.message){
      error.value = err.message;
    }
    error.value = 'Network error. Please try again.';
  }
}
</script>

<style scoped>
/* ---------- Layout ---------- */
.login-page {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #eaf1ff 0%, #f8f8ff 100%);
  padding: 1rem;
}

.login-card {
  width: 100%;
  max-width: 420px;
  padding: 2.25rem 2.5rem;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.05);
}

.card-title {
  margin-bottom: 1.75rem;
  font-size: 1.65rem;
  font-weight: 700;
  text-align: center;
}

.brand {
  color: #16c925;
}

/* ---------- Form ---------- */
.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-field label {
  display: block;
  margin-bottom: 0.35rem;
  font-weight: 600;
  font-size: 0.9rem;
  color: #333;
}

.form-field input {
  width: 100%;
  padding: 0.65rem 0.85rem;
  border: 1px solid #d0d6f5;
  border-radius: 6px;
  font-size: 0.95rem;
  transition: border-color 0.2s;
}

.form-field input:focus {
  outline: none;
  border-color: #3366ff;
  box-shadow: 0 0 0 2px rgba(51, 102, 255, 0.1);
}

.btn-submit {
  width: 100%;
  padding: 0.75rem 1rem;
  background: #16c925;
  color: #fff;
  font-weight: 600;
  font-size: 1rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-submit:hover {
  background: #264fcc;
}

/* ---------- Links & Messages ---------- */
.register-link {
  margin-top: 0.75rem;
  text-align: center;
  font-size: 0.9rem;
}

.link {
  color: #3366ff;
  font-weight: 600;
  text-decoration: none;
}

.link:hover {
  text-decoration: underline;
}

.error {
  margin-top: 0.5rem;
  color: #e63946;
  font-size: 0.9rem;
  text-align: center
}

.success {
  margin-top: 0.5rem;
  color: #2a9d8f;
  font-size: 0.9rem;
  text-align: center;
}

</style>
