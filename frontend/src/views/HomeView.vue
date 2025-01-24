<template>
  <div>
    <!-- Hero Section -->
    <header class="bg-light py-5">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <h1 class="display-4 mb-4" style="font-family: 'Playwrite US Modern Guides'">THERE ARE OVER THOUSANDS JOBS
              HERE FOR YOU!</h1>
            <p class="lead text-muted mb-4">
              Connect with top employers and discover exciting career opportunities across various industries.
            </p>

            <!-- Job Search Form -->
            <form @submit.prevent="searchJobs" class="mb-4">
              <div class="input-group">
                <input type="text" class="form-control form-control-lg" placeholder="Job title or keyword"
                  v-model="jobKeyword">
                <input type="text" class="form-control form-control-lg" placeholder="Location" v-model="jobLocation">
                <button class="btn btn-primary" type="submit">
                  Search Jobs
                </button>
              </div>
            </form>

            <div class="d-flex align-items-center">
              <span class="me-3 text-muted">Popular Searches:</span>
              <div>
                <span v-for="tag in popularTags" :key="tag" class="badge bg-light text-dark me-2 mb-2">
                  {{ tag }}
                </span>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <img src="/src/assets/images/jobseeker.avif" alt="Job Search Illustration" class="img-fluid rounded-pill">
          </div>
        </div>
      </div>
    </header>

    <!-- Featured Job Categories -->
    <section class="py-5">
      <div class="container">
        <h2 class="text-center mb-5">Browse Job Categories</h2>
        <div class="row">
          <div v-for="category in jobCategories" :key="category.name" class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body text-center">
                <i :class="category.icon" class="mb-3 text-primary" style="font-size: 3rem;"></i>
                <h5 class="card-title">{{ category.name }}</h5>
                <p class="card-text text-muted">{{ category.jobCount }} Open Positions</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <section class="bg-light py-5">
    <div class="container">
      <h2 class="text-center mb-5 fw-bold">How It Works</h2>
      
      <div class="row justify-content-center g-4">
        <!-- Step 1: Create Profile -->
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 text-center p-4 hover-card">
            <div class="mx-auto mb-4">
              <div class="step-circle bg-primary text-white d-flex align-items-center justify-content-center">
                <span class="h4 mb-0">1</span>
              </div>
            </div>
            <router-link to="/Registration" class="h5 text-decoration-none text-dark mb-3">
              Create Profile
            </router-link>
            <p class="text-muted mb-0">
              Build your professional profile and showcase your skills to potential employers
            </p>
          </div>
        </div>

        <!-- Step 2: Find Jobs -->
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 text-center p-4 hover-card">
            <div class="mx-auto mb-4">
              <div class="step-circle bg-primary text-white d-flex align-items-center justify-content-center">
                <span class="h4 mb-0">2</span>
              </div>
            </div>
            <router-link to="/jobs" class="h5 text-decoration-none text-dark mb-3">
              Find Jobs
            </router-link>
            <p class="text-muted mb-0">
              Search and filter through thousands of job opportunities matching your skills
            </p>
          </div>
        </div>

        <!-- Step 3: Apply -->
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100 text-center p-4 hover-card">
            <div class="mx-auto mb-4">
              <div class="step-circle bg-primary text-white d-flex align-items-center justify-content-center">
                <span class="h4 mb-0">3</span>
              </div>
            </div>
            <h5 class="mb-3">Apply</h5>
            <p class="text-muted mb-0">
              Submit applications directly and track your application status in real-time
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      jobKeyword: '',
      jobLocation: '',
      popularTags: ['Software', 'Marketing', 'Design', 'Finance', 'Engineering'],
      jobCategories: [
        { name: 'Technology', icon: 'fas fa-laptop-code', jobCount: 453 },
        { name: 'Marketing', icon: 'fas fa-bullhorn', jobCount: 276 },
        { name: 'Design', icon: 'fas fa-palette', jobCount: 189 }
      ]
    }
  },
  methods: {
    searchJobs() {
      console.log('searching jobs:', this.jobKeyword, this.jobLocation)
    },
    goToProfile() {
      console.log('Navigating to the profile creation page...');
      this.$router.push({ name: 'Registration' });
    }
  }
}
</script>

<style scoped>
.step-circle {
  width: 60px;
  height: 60px;
  border-radius: 50%;
}

.hover-card {
  transition: transform 0.3s ease;
}

.hover-card:hover {
  transform: translateY(-5px);
}
</style>
