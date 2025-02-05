<template>
  <div class="container py-5">
    <div class="text-center mb-4">
      <h1 class="display-5 fw-bold">Contact Us</h1>
      <p class="lead text-muted">
        Have questions or need assistance? We’re here to help! Please fill out the form below, and our team will get back to you promptly.
      </p>
    </div>

    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow">
          <div class="card-body p-4">
            <form @submit.prevent="handleSubmit">
              <!-- Name -->
              <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Full Name</label>
                <input
                  type="text"
                  id="name"
                  v-model="form.name"
                  class="form-control"
                  :class="{ 'is-invalid': errors.name }"
                  placeholder="Enter your full name"
                />
                <div v-if="errors.name" class="invalid-feedback">
                  {{ errors.name }}
                </div>
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input
                  type="email"
                  id="email"
                  v-model="form.email"
                  class="form-control"
                  :class="{ 'is-invalid': errors.email }"
                  placeholder="Enter your email address"
                />
                <div v-if="errors.email" class="invalid-feedback">
                  {{ errors.email }}
                </div>
              </div>

              <!-- Message -->
              <div class="mb-3">
                <label for="message" class="form-label fw-semibold">Message</label>
                <textarea
                  id="message"
                  v-model="form.message"
                  rows="5"
                  class="form-control"
                  :class="{ 'is-invalid': errors.message }"
                  placeholder="Write your message here"
                ></textarea>
                <div v-if="errors.message" class="invalid-feedback">
                  {{ errors.message }}
                </div>
              </div>

              <!-- Submit Button -->
              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                  Submit
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ContactUs",
  data() {
    return {
      form: {
        name: "",
        email: "",
        message: "",
      },
      errors: {
        name: null,
        email: null,
        message: null,
      },
    };
  },
  methods: {
    validateForm() {
      // Reset errors
      this.errors = { name: null, email: null, message: null };
      let isValid = true;

      if (!this.form.name.trim()) {
        this.errors.name = "Full name is required.";
        isValid = false;
      }

      if (!this.form.email.trim()) {
        this.errors.email = "Email address is required.";
        isValid = false;
      } else if (!/\S+@\S+\.\S+/.test(this.form.email)) {
        this.errors.email = "Please enter a valid email address.";
        isValid = false;
      }

      if (!this.form.message.trim()) {
        this.errors.message = "Message cannot be empty.";
        isValid = false;
      }

      return isValid;
    },
    handleSubmit() {
      if (this.validateForm()) {
        alert("Your message has been submitted successfully. We'll get back to you soon!");
        console.log("Submitted form data:", this.form);

        // Reset form after submission
        this.form.name = "";
        this.form.email = "";
        this.form.message = "";
      }
    },
  },
};
</script>

<style scoped>
.container {
  max-width: 900px;
}
.card {
  border-radius: 12px;
}
.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
}
.btn-primary:hover {
  background-color: #0056b3;
  border-color: #004085;
}
</style>
