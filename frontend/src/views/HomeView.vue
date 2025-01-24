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

    <!-- Search Results Section -->
    <section v-if="isSearchPerformed" class="py-5">
      <div class="container">
        <!-- Error State -->
        <div v-if="searchError" class="alert alert-warning text-center" role="alert">
          {{ searchError }}
        </div>

        <!-- Results State -->
        <div v-else-if="searchResults.length > 0">
          <h2 class="text-center mb-4">Search Results</h2>
          <div class="row">
            <div v-for="job in searchResults" :key="job.id" class="col-md-4 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <h5 class="card-title">{{ job.title }}</h5>
                  <p class="card-text text-muted">
                    <i class="bi bi-geo-alt me-2"></i>{{ job.location }}
                  </p>
                  <p class="card-text">{{ job.description.slice(0, 100) }}...</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-primary">{{ job.type }}</span>
                    <button 
                      @click="goToJobDetails(job.id)" 
                      class="btn btn-sm btn-outline-secondary"
                    >
                      View Details
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

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
import { useJobStore } from '@/stores/jobStore';
import { ref } from 'vue';

export default {
  setup() {
    const jobStore = useJobStore();
    const jobKeyword = ref('');
    const jobLocation = ref('');
    const searchResults = ref([]);
    const isSearchPerformed = ref(false);
    const searchError = ref(null);

    return {
      jobStore,
      jobKeyword,
      jobLocation,
      searchResults,
      isSearchPerformed,
      searchError
    };
  },
  data() {
    return {
      popularTags: ['Software', 'Marketing', 'Design', 'Finance', 'Engineering'],
      jobCategories: [
        { name: 'Technology', icon: 'fas fa-laptop-code', jobCount: 453 },
        { name: 'Marketing', icon: 'fas fa-bullhorn', jobCount: 276 },
        { name: 'Design', icon: 'fas fa-palette', jobCount: 189 }
      ]
    }
  },
  methods: {
    async searchJobs() {
      // Reset previous search state
      this.searchResults = [];
      this.isSearchPerformed = true;
      this.searchError = null;

      // Prepare search parameters
      const searchParams = {};
      if (this.jobKeyword.trim()) searchParams.query = this.jobKeyword.trim();
      if (this.jobLocation.trim()) searchParams.location = this.jobLocation.trim();

      try {
        // Perform job search
        const results = await this.jobStore.searchJobs(searchParams);
        
        this.searchResults = results;

        // Set error if no results found
        if (this.searchResults.length === 0) {
          this.searchError = 'No jobs found matching your search criteria.';
        }
      } catch (error) {
        console.error('Job search error:', error);
        this.searchError = 'Unable to perform search. Please try again later.';
      }
    },
    goToJobDetails(jobId) {
      this.$router.push({ name: 'job-details', params: { id: jobId } });
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
