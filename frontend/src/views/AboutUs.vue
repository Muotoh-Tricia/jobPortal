<template>
  <div class="about-view">
    <div class="hero-section bg-primary text-white py-5">
      <div class="container">
        <h1 class="display-4 fw-bold text-center">About JobMe</h1>
        <p class="lead text-center">Connecting Talent with Opportunity</p>
      </div>
    </div>

    <div class="container py-5">
      <div class="row gy-4">
        <!-- Mission Section -->
        <div class="col-md-12 mb-5">
          <h2 class="fw-bold mb-4">Our Mission</h2>
          <p class="lead">At JobMe, we're dedicated to transforming the way people find their dream careers and how companies discover exceptional talent. Our platform serves as a bridge between ambitious professionals and forward-thinking organizations.</p>
        </div>

        <!-- Who We Are Section -->
        <div class="col-md-6">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
              <h3 class="card-title mb-3">Who We Are</h3>
              <p class="card-text">Founded in 2024, JobMe has grown to become a leading force in the digital recruitment space. We understand that finding the right job or the perfect candidate is more than just matching keywords - it's about creating meaningful connections that drive careers and businesses forward.</p>
            </div>
          </div>
        </div>

        <!-- What We Do Section -->
        <div class="col-md-6">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
              <h3 class="card-title mb-3">What We Do</h3>
              <p class="card-text">We leverage cutting-edge technology and human insight to create a seamless job search and recruitment experience. Our platform offers sophisticated job matching, real-time notifications, and powerful tools for both job seekers and employers.</p>
            </div>
          </div>
        </div>

        <!-- Our Values Section -->
        <div class="col-md-12 mt-5">
          <h2 class="fw-bold mb-4">Our Values</h2>
          <div class="row g-4">
            <div class="col-md-4">
              <div class="value-card text-center p-4">
                <i class="bi bi-lightning-charge-fill text-primary fs-1"></i>
                <h4 class="mt-3">Innovation</h4>
                <p>Continuously evolving our platform to meet the changing needs of the job market.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="value-card text-center p-4">
                <i class="bi bi-shield-check text-primary fs-1"></i>
                <h4 class="mt-3">Integrity</h4>
                <p>Maintaining the highest standards of transparency and ethical practices.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="value-card text-center p-4">
                <i class="bi bi-people-fill text-primary fs-1"></i>
                <h4 class="mt-3">Community</h4>
                <p>Building meaningful connections between professionals and organizations.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AboutView'
}
</script>

<style scoped>
.value-card {
  border: 1px solid #eee;
  border-radius: 8px;
  transition: transform 0.3s ease;
}

.value-card:hover {
  transform: translateY(-5px);
}

.hero-section {
  background: linear-gradient(45deg, #007bff, #0056b3);
}
</style>