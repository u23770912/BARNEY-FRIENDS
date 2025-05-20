<template>
  <div>
    <!-- Header -->
    <header>
      <div class="header-links">
        <router-link to="/login">Login</router-link>
      </div>
    </header>

    <!-- Main Content Container -->
    <div class="main-container">
      <div class="form-container">
        <h2>Create an Account</h2>
        <form @submit.prevent="handleSubmit">
          <label for="name">Name</label>
          <input type="text" id="name" v-model="form.name" required />

          <label for="surname">Surname</label>
          <input type="text" id="surname" v-model="form.surname" required />

          <label for="email">Email</label>
          <input type="email" id="email" v-model="form.email" required />

          <label for="password">Password</label>
          <input type="password" id="password" v-model="form.password" required />

          <label for="user_type">User Type</label>
          <select id="user_type" v-model="form.user_type" required>
            <option value="">-- Select User Type --</option>
            <option value="Customer">Customer</option>
            <option value="Courier">Courier</option>
          </select>

          <button type="submit">Register</button>
          <div v-if="errorMessage" class="error-message">{{ errorMessage }}</div>
          <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Signup',
  data() {
    return {
      form: {
        name: '',
        surname: '',
        email: '',
        password: '',
        user_type: '',
      },
      errorMessage: '',
      successMessage: '',
    };
  },
  methods: {
    async handleSubmit() {
      try {
        const response = await fetch('path/to/your/api.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            type: 'Register',
            ...this.form,
          }),
        });

        const data = await response.json();

        if (data.error) {
          this.errorMessage = data.error.message || 'An error occurred.';
          this.successMessage = '';
        } else {
          this.successMessage = 'Registration successful!';
          this.errorMessage = '';
        }
      } catch (error) {
        this.errorMessage = 'Request failed. Please try again.';
        this.successMessage = '';
      }
    },
  },
};
</script>

<style scoped>
@import '@/assets/css/signup.css';
</style>
