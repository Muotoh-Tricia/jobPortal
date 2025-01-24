<template>
    <footer class="bg-primary text-white">
        <div class="container py-5">
            <div class="row">
                <!-- About Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-uppercase mb-3">JobPortal</h5>
                    <p>
                        JobPortal connects job seekers with employers, offering a platform
                        to explore opportunities, post jobs, and streamline the hiring
                        process.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-uppercase mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li>
                            <router-link to="/" class="text-white text-decoration-none">Home</router-link>
                        </li>
                        <li>
                            <router-link to="/jobs" class="text-white text-decoration-none">Find Jobs</router-link>
                        </li>
                        <li>
                            <router-link to="/post-job" class="text-white text-decoration-none">Post a Job</router-link>
                        </li>
                        <li>
                            <router-link to="/contact" class="text-white text-decoration-none">Contact Us</router-link>
                        </li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <h5 class="text-uppercase mb-3">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope me-2"></i> support@jobportal.com</li>
                        <li><i class="bi bi-telephone me-2"></i> +234 123-45674</li>
                        <li>
                            <i class="bi bi-geo-alt me-2"></i> 123 GRA, Lagos, Nigeria
                        </li>
                    </ul>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>

            <hr class="bg-secondary" />

            <div class="text-center py-3">
                <p class="mb-0">&copy; 2025 JobMe. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
};
</script>

<style scoped>
footer a:hover {
    color: #007bff;
    text-decoration: underline;
}

footer .bi {
    font-size: 20px;
}
</style>
