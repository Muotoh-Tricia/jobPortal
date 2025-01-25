
<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Apply for Job: {{ job ? job.title : 'Loading...' }}</h2>
          </div>
          <div class="card-body">
            <form @submit.prevent="submitApplication">
              <!-- Job Seeker Profile Section -->
              <div class="mb-3">
                <h4>Personal Information</h4>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input 
                      type="text" 
                      class="form-control" 
                      id="name" 
                      v-model="applicationForm.name"
                      :class="{'is-invalid': validationErrors.name}"
                      required
                    >
                    <div v-if="validationErrors.name" class="invalid-feedback">
                      {{ validationErrors.name }}
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                      type="email" 
                      class="form-control" 
                      id="email" 
                      v-model="applicationForm.email"
                      :class="{'is-invalid': validationErrors.email}"
                      required
                    >
                    <div v-if="validationErrors.email" class="invalid-feedback">
                      {{ validationErrors.email }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contact Information -->
              <div class="mb-3">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input 
                      type="tel" 
                      class="form-control" 
                      id="phone" 
                      v-model="applicationForm.phone"
                      :class="{'is-invalid': validationErrors.phone}"
                      required
                    >
                    <div v-if="validationErrors.phone" class="invalid-feedback">
                      {{ validationErrors.phone }}
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="location" class="form-label">Current Location</label>
                    <input 
                      type="text" 
                      class="form-control" 
                      id="location" 
                      v-model="applicationForm.location"
                      :class="{'is-invalid': validationErrors.location}"
                      required
                    >
                    <div v-if="validationErrors.location" class="invalid-feedback">
                      {{ validationErrors.location }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Cover Letter -->
              <div class="mb-3">
                <label for="cover_letter" class="form-label">Cover Letter</label>
                <textarea 
                  class="form-control" 
                  id="cover_letter" 
                  rows="5" 
                  v-model="applicationForm.cover_letter"
                  :class="{'is-invalid': validationErrors.cover_letter}"
                  placeholder="Why are you a great fit for this role? (Optional)"
                ></textarea>
                <div v-if="validationErrors.cover_letter" class="invalid-feedback">
                  {{ validationErrors.cover_letter }}
                </div>
              </div>

              <!-- Resume Upload -->
              <div class="mb-3">
                <label for="resume" class="form-label">Resume/CV</label>
                <input 
                  type="file" 
                  class="form-control" 
                  id="resume" 
                  @change="handleResumeUpload"
                  accept=".pdf,.doc,.docx"
                >
                <small class="text-muted">Accepted formats: PDF, DOC, DOCX (Max 5MB)</small>
              </div>

              <!-- Submit Button -->
              <div class="d-grid">
                <button 
                  type="submit" 
                  class="btn btn-primary" 
                  :disabled="isSubmitting"
                >
                  <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
                  Submit Application
                </button>
              </div>
            </form>

            <!-- Submission Status Messages -->
            <div v-if="submissionSuccess" class="alert alert-success mt-3">
              Application submitted successfully!
            </div>
            <div v-if="submissionError" class="alert alert-danger mt-3">
              {{ submissionError }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';
import apiClient from '@/services/apiClient';

export default {
  name: 'JobApplicationView',
  setup() {
    const route = useRoute();
    const router = useRouter();
    const authStore = useAuthStore();

    return { route, router, authStore };
  },
  data() {
    return {
      job: null,
      applicationForm: {
        name: '',
        email: '',
        phone: '',
        location: '',
        cover_letter: '',
        resume: null
      },
      validationErrors: {},
      isSubmitting: false,
      submissionSuccess: false,
      submissionError: null
    };
  },
  async created() {
    try {
      // Fetch job details
      const jobId = this.route.params.jobId;
      const jobResponse = await apiClient.get(`/v1/jobs/${jobId}`);
      this.job = jobResponse.data;

      // Pre-fill user information if logged in
      const currentUser = this.authStore.user;
      if (currentUser) {
        this.applicationForm.name = currentUser.name;
        this.applicationForm.email = currentUser.email;
      }
    } catch (error) {
      console.error('Error fetching job details:', error);
      this.submissionError = 'Unable to load job details';
    }
  },
  methods: {
    handleResumeUpload(event) {
      const file = event.target.files[0];
      
      // Basic file validation
      if (file) {
        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        if (file.size > maxSize) {
          alert('File is too large. Maximum size is 5MB.');
          event.target.value = null;
          return;
        }

        if (!allowedTypes.includes(file.type)) {
          alert('Invalid file type. Please upload PDF or Word documents.');
          event.target.value = null;
          return;
        }

        this.applicationForm.resume = file;
      }
    },
    async submitApplication() {
      // Reset previous states
      this.validationErrors = {};
      this.submissionSuccess = false;
      this.submissionError = null;
      this.isSubmitting = true;

      try {
        // Prepare form data for multipart upload
        const formData = new FormData();
        
        // Ensure job_id is set
        if (!this.job || !this.job.id) {
          throw new Error('Job details are missing');
        }
        formData.append('job_id', this.job.id);
        
        // Add personal information
        formData.append('name', this.applicationForm.name);
        formData.append('email', this.applicationForm.email);
        formData.append('phone', this.applicationForm.phone);
        formData.append('location', this.applicationForm.location);
        
        // Optional cover letter
        if (this.applicationForm.cover_letter) {
          formData.append('cover_letter', this.applicationForm.cover_letter);
        }

        // Resume upload
        if (this.applicationForm.resume) {
          formData.append('resume', this.applicationForm.resume);
        }

        // Submit application
        const response = await apiClient.post('/v1/applications', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        this.submissionSuccess = true;
        
        // Navigate to applications or show success message
        setTimeout(() => {
          this.router.push('/applications');
        }, 2000);
      } catch (error) {
        console.error('Application Submission Error:', error);
        
        // Handle validation errors
        if (error.response && error.response.data.errors) {
          this.validationErrors = error.response.data.errors;
        }

        // Handle other errors
        this.submissionError = error.response?.data?.error || 'An unexpected error occurred';
      } finally {
        this.isSubmitting = false;
      }
    }
  }
}
</script>

<style scoped>
.card-header {
  background-color: #007bff !important;
  color: white;
}

.form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
