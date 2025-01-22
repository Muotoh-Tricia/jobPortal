<template>
  <div class="container mt-5">
    <h2 class="text-center mb-4">Registration Form</h2>

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
        <input type="password" class="form-control" id="password" v-model="password" required>
      </div>

      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="confirmPassword" v-model="confirmPassword" required>
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="agree" v-model="agree" required>
        <label class="form-check-label" for="agree">I agree to the terms and conditions</label>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-5">Register</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      confirmPassword: '',
      agree: false
    }
  },
  methods: {
    submitForm() {
      if (this.password !== this.confirmPassword) {
        alert('Passwords do not match');
        return;
      }

      if (!this.agree) {
        alert('You must agree to the terms and conditions');
        return;
      }
      alert('Registration successful!');
    }
  }
}
</script>

<style scoped>
.container {
  max-width: 500px;
}
</style>
