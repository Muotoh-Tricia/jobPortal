<template>
  <div class="job-portal-landing">
    <nav class="navbar navbar-expand-lg navbar-light bg-primary">
      <div class="container">
        <router-link to="/" class="navbar-brand text-white"><i class="bi bi-briefcase"/> JobMe</router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link to="/" class="nav-link text-white">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/jobs" class="nav-link text-white">Jobs</router-link>
            </li>
            
            <!-- Guest Navigation -->
            <template v-if="!isUserAuthenticated">
              <li class="nav-item">
                <router-link to="/login" class="nav-link text-white">Login</router-link>
              </li>
              <li class="nav-item">
                <router-link to="/registration" class="nav-link text-white">Registration</router-link>
              </li>
            </template>

            <!-- Job Seeker Navigation -->
            <template v-else-if="isJobSeeker">
              <li class="nav-item">
                <router-link to="/profile" class="nav-link text-white">Profile</router-link>
              </li>
              <li class="nav-item">
                <router-link to="/my-applications" class="nav-link text-white">My Applications</router-link>
              </li>
              <li class="nav-item">
                <a @click="handleLogout" href="#" class="nav-link text-white">Logout</a>
              </li>
            </template>

            <!-- Employer Navigation -->
            <template v-else-if="isEmployer">
              <li class="nav-item">
                <router-link to="/profile" class="nav-link text-white">Profile</router-link>
              </li>
              <li class="nav-item">
                <router-link to="/post-job" class="nav-link text-white">Post Jobs</router-link>
              </li>
              <li class="nav-item">
                <a @click="handleLogout" href="#" class="nav-link text-white">Logout</a>
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
    // Optional: show error message to user
    console.error('Logout failed', error);
    // Force redirect to login even if logout fails
    this.$router.push('/login');
  }
}
  }
}
</script>

<style scoped>
nav {
  padding: 30px;
}

.navbar-nav .nav-link {
  font-weight: 500;
  padding: 0.5rem 1rem;
  transition: color 0.2s;
}

.navbar-nav .nav-link:hover {
  color: rgba(255, 255, 255, 0.8) !important;
}

.navbar-brand {
  font-size: 1.5rem;
  font-weight: 600;
}

.navbar-brand i {
  margin-right: 0.5rem;
}
</style>
