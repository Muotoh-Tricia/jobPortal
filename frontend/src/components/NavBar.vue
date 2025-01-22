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
            <li class="nav-item">
              <router-link to="/login" class="nav-link text-white">Login</router-link>
            </li>
             <li class="nav-item">
              <router-link to="/registration" class="nav-link text-white">Registration</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</template>

<script>
export default {
  name: 'NavBar'
};
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
