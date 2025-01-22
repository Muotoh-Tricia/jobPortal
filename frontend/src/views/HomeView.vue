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
        <h2 class="text-center mb-5">How It Works</h2>

        <div class="d-flex justify-content-center">

          <div class="row mb-4">
            <div class="col-md-4 text-center">
              <div
                class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white"
                style="width: 80px; height: 80px;">
                <span class="h4 mb-0">1</span>
              </div>
              <router-link to="/Registration" class="text-decoration-none">
                Create Profile
              </router-link>
              <p class="text-muted">Build a comprehensive professional profile</p>
            </div>
          </div>


          <div class="row mb-4">
            <div class="col-md-4 text-center">
              <div
                class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white"
                style="width: 80px; height: 80px;">
                <span class="h4 mb-0">2</span>
              </div>
              <router-link to="/FindJob" class="text-decoration-none">
                Find Jobs
              </router-link>
              <p class="text-muted">Search and filter jobs matching your skills</p>
            </div>
          </div>


          <div class="row mb-4">
            <div class="col-md-4 text-center">
              <div
                class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-primary text-white"
                style="width: 80px; height: 80px;">
                <span class="h4 mb-0">3</span>
              </div>
              <h5>Apply</h5>
              <p class="text-muted">Submit applications directly through our platform</p>
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

<style lang="scss" scoped></style>
