<template>
  <div class="job-portal-landing">
    <nav class="navbar navbar-expand-lg navbar-light bg-primary shadow-lg sticky-top">
      <div class="container">
        <router-link to="/" class="navbar-brand text-white fw-bold">
          <i class="bi bi-briefcase-fill fs-4"></i>
          <span class="ms-2">JobMe</span>
        </router-link>

        <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse py-2" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item mx-1">
              <router-link to="/" class="nav-link text-white px-3">
                <i class="bi bi-house-door-fill me-1"></i> Home
              </router-link>
            </li>
            <li class="nav-item mx-1">
              <router-link to="/jobs" class="nav-link text-white px-3">
                <i class="bi bi-search me-1"></i> Jobs
              </router-link>
            </li>
            <li class="nav-item mx-1">
              <router-link to="/about" class="nav-link text-white px-3">
                <i class="bi bi-info-circle me-1"></i> About
              </router-link>
            </li>
            <li class="nav-item mx-1">
              <router-link to="/contact" class="nav-link text-white px-3">
                <i class="bi bi-envelope me-1"></i> Contact
              </router-link>
            </li>

            <!-- Guest Navigation -->
            <template v-if="!isUserAuthenticated">
              <li class="nav-item mx-1">
                <router-link to="/login" class="nav-link text-white px-3">
                  <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </router-link>
              </li>
              <li class="nav-item mx-1">
                <router-link to="/registration"
                  class="nav-link text-white px-3 py-2 bg-white bg-opacity-25 rounded-pill">
                  <i class="bi bi-person-plus-fill me-1"></i> Registration
                </router-link>
              </li>
            </template>

            <!-- Job Seeker Navigation -->
            <template v-else-if="isJobSeeker">
              <li class="nav-item mx-1">
                <router-link to="/profile" class="nav-link text-white px-3">
                  <i class="bi bi-person-circle me-1"></i> Profile
                </router-link>
              </li>
              <li class="nav-item mx-1">
                <router-link to="/my-applications" class="nav-link text-white px-3">
                  <i class="bi bi-file-earmark-text me-1"></i> My Applications
                </router-link>
              </li>
              <li class="nav-item mx-1">
                <a @click="handleLogout" href="#" class="nav-link text-white px-3">
                  <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
              </li>
            </template>

            <!-- Employer Navigation -->
            <template v-else-if="isEmployer">
              <li class="nav-item mx-1">
                <router-link to="/profile" class="nav-link text-white px-3">
                  <i class="bi bi-building me-1"></i> Profile
                </router-link>
              </li>
              <li class="nav-item mx-1">
                <router-link to="/post-job" class="nav-link text-white px-3 py-2 bg-white bg-opacity-25 rounded-pill">
                  <i class="bi bi-plus-circle-fill me-1"></i> Post Jobs
                </router-link>
              </li>
              <li class="nav-item mx-1">
                <a @click="handleLogout" href="#" class="nav-link text-white px-3">
                  <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
import authService from '@/services/authService';

export default {
  name: 'NavBar',
  data() {
    return {
      currentUser: null
    }
  },
  computed: {
    isUserAuthenticated() {
      return authService.isAuthenticated();
    },
    isJobSeeker() {
      const user = authService.getCurrentUser();
      return this.isUserAuthenticated && user && user.role === 'jobseeker';
    },
    isEmployer() {
      const user = authService.getCurrentUser();
      return this.isUserAuthenticated && user && user.role === 'employer';
    }
  },
  methods: {
    async handleLogout() {
      try {
        await authService.logout();
        this.$router.push('/login');
      } catch (error) {
        console.error('Logout failed', error);
        this.$router.push('/login');
      }
    }
  }
}
</script>

<style scoped></style>