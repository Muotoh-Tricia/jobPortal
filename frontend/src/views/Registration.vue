<template>
  <div class="container mt-5">
    <h2 class="text-center mb-4">Registration</h2>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <form @submit.prevent="submitForm">
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" v-model="name" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" v-model="email" required>
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

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input 
          type="password" 
          class="form-control" 
          id="password_confirmation" 
          v-model="password_confirmation" 
          required
        >
      </div>

      <div class="mb-3">
        <label class="form-label">Register As</label>
        <div class="d-flex">
          <div class="form-check me-3">
            <input 
              class="form-check-input" 
              type="radio" 
              name="role" 
              id="jobseeker" 
              value="jobseeker" 
              v-model="role" 
              required
            >
            <label class="form-check-label" for="jobseeker">Job Seeker</label>
          </div>
          <div class="form-check">
            <input 
              class="form-check-input" 
              type="radio" 
              name="role" 
              id="employer" 
              value="employer" 
              v-model="role" 
              required
            >
            <label class="form-check-label" for="employer">Employer</label>
          </div>
        </div>
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="agree" v-model="agree" required>
        <label class="form-check-label" for="agree">I agree to the terms and conditions</label>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3" :disabled="isLoading">
        <span v-if="isLoading" class="spinner-border spinner-border-sm me-1"></span>
        Register
      </button>

      <div class="text-center mb-5">
        Already have an account? 
        <router-link to="/login" class="text-decoration-none">Login here</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import authService from '@/services/authService';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      role: '',
      agree: false,
      error: null,
      isLoading: false
    }
  },
  methods: {
    async submitForm() {
      // Reset error
      this.error = null;

      // Validate inputs
      if (this.password !== this.password_confirmation) {
        this.error = 'Passwords do not match';
        return;
      }

      if (!this.agree) {
        this.error = 'You must agree to the terms and conditions';
        return;
      }

      // Prepare registration data
      const userData = {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.password_confirmation,
        role: this.role
      };

      try {
        // Set loading state
        this.isLoading = true;

        // Call registration service
        const response = await authService.register(userData);

        // Redirect to login or dashboard
        this.$router.push('/login');
      } catch (error) {
        // Set error message
        this.error = error.message || 'Registration failed';
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
