<template>
  <div class="signup-page">
    <div class="signup-card">
      <h2 class="card-title">Create your <span class="brand">CompareIT</span> account</h2>

      <!-- Dummy signup form – replace submit handler with real API call later -->
      <form class="signup-form" @submit.prevent="onSubmit">
        
          <div class="form-field">
            <label for="name">First Name</label>
            <input
              type="text"
              id="name"
              v-model="name"
              placeholder="John"
              required
            />
          </div>

          <div class="form-field">
            <label for="surname">Last Name</label>
            <input
              type="text"
              id="surname"
              v-model="surname"
              placeholder="Smith"
              required
            />
          </div>
        
        <div class="form-field">
          <label for="email">Email</label>
          <input
            type="email"
            id="email"
            v-model="email"
            placeholder="you@example.com"
            required
          />
        </div>

        <div class="form-field">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            v-model="password"
            placeholder="Create a password"
            required
          />
        </div>

        <div class="form-field">
          <label for="confirm">Confirm Password</label>
          <input
            type="password"
            id="confirm"
            v-model="confirm"
            placeholder="Repeat your password"
            required
          />
        </div>

        <button type="submit" class="btn-submit">Sign&nbsp;Up</button>

        <p class="login-link">
          Already have an account?
          <router-link to="/login" class="link">Log in</router-link>
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

  const email = ref('');
  const password = ref('');
  const name = ref('');
  const surname = ref('');
  const confirm = ref('');
  const error = ref('');
  const success = ref('');
  const router = useRouter();

  // Regexes
  const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
  // Simplified and more robust
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).{8,}$/;

  async function onSubmit() {
    error.value = '';
    success.value = '';

    // --- Basic client validation ---
    if (!name.value || !surname.value || !email.value || !password.value || !confirm.value) {
      error.value = 'All fields are required.';
      return;
    }

    if (!emailRegex.test(email.value)) {
      error.value = 'Please enter a valid email address.';
      return;
    }

    if (!passwordRegex.test(password.value)) {
      error.value =
        'Password must be at least 8 characters, include upper/lowercase, number, and special character.';
      return;
    }

    if (password.value !== confirm.value) {
      error.value = 'Passwords do not match.';
      return;
    }

    // --- API Call ---
    try {
      const data = await useApi({
        type: 'Register',
        name: name.value,
        surname: surname.value,
        email: email.value,
        password: password.value,
      });

    if (data.status === 'success' && data.data?.apikey) {
      success.value = 'Signup successful! Redirecting to login...';
      setTimeout(() => router.push('/login'), 1500);
    } else if (data.status === 'success' && !data.data?.apikey) {
      // This is likely a registration success but no apikey returned
      success.value = 'Signup successful but no API key received. Try logging in.';
      setTimeout(() => router.push('/login'), 2000);
    } else {
      error.value = data.message || 'Signup failed. Please try again.';
    }
    } catch (err) {
      error.value = 'Network error. Please try again.';
    }
  }
</script>



<style scoped>
/* ---------- Layout ---------- */
.signup-page {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #fffaf6 0%, #f6faff 100%);
  padding: 1rem;
}

.signup-card {
  width: 100%;
  max-width: 460px;
  padding: 2.5rem 2.75rem;
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
.signup-form {
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
.login-link {
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
  text-align: center;
}

.success {
  margin-top: 0.5rem;
  color: #2a9d8f;
  font-size: 0.9rem;
  text-align: center;
}
</style>
