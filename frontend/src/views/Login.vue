<template>
  <div class="container mt-5">
    <h2 class="text-center mb-4">Login</h2>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <form @submit.prevent="submitForm">
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input 
          type="email" 
          class="form-control" 
          id="email" 
          v-model="email" 
          required
        >
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input 
          type="password" 
          class="form-control" 
          id="password" 
          v-model="password" 
          required
        >
      </div>

      <div class="mb-3 form-check">
        <input 
          type="checkbox" 
          class="form-check-input" 
          id="rememberMe" 
          v-model="rememberMe"
        >
        <label class="form-check-label" for="rememberMe">
          Remember me
        </label>
      </div>

      <button 
        type="submit" 
        class="btn btn-primary w-100 mb-3" 
        :disabled="isLoading"
      >
        <span v-if="isLoading" class="spinner-border spinner-border-sm me-1"></span>
        Login
      </button>

      <div class="text-center">
        Don't have an account? 
        <router-link to="/register" class="link-primary">
          Register here
        </router-link>
      </div>
    </form>
  </div>
</template>

<script>
import authService from '@/services/authService';

export default {
  data() {
    return {
      email: '',
      password: '',
      rememberMe: false,
      error: null,
      isLoading: false
    }
  },
  methods: {
    async submitForm() {
      // Reset error
      this.error = null;

      // Prepare login credentials
      const credentials = {
        email: this.email,
        password: this.password
      };

      try {
        // Set loading state
        this.isLoading = true;

        // Call login service
        const response = await authService.login(credentials);

        // Redirect based on user role
        const user = authService.getCurrentUser();
        if (user.role === 'employer') {
          this.$router.push('/employers');
        } else if (user.role === 'jobseeker') {
          this.$router.push('/job-seekers');
        } else {
          this.$router.push('/');
        }
      } catch (error) {
        // Set error message
        this.error = error.message || 'Login failed';
      } finally {
        // Reset loading state
        this.isLoading = false;
      }
    }
  }
}
</script>

<style scoped>
.container {
  max-width: 500px;
}
</style>
