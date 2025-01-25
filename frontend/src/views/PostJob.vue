<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Post a New Job</h2>
          </div>
          <div class="card-body">
            <form @submit.prevent="submitJob">
              <!-- Job Title -->
              <div class="mb-3">
                <label for="title" class="form-label">Job Title</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="title" 
                  v-model="jobForm.title" 
                  :class="{'is-invalid': validationErrors.title}"
                  required
                >
                <div v-if="validationErrors.title" class="invalid-feedback">
                  {{ validationErrors.title }}
                </div>
              </div>

              <!-- Job Description -->
              <div class="mb-3">
                <label for="description" class="form-label">Job Description</label>
                <textarea 
                  class="form-control" 
                  id="description" 
                  rows="4" 
                  v-model="jobForm.description"
                  :class="{'is-invalid': validationErrors.description}"
                  required
                ></textarea>
                <div v-if="validationErrors.description" class="invalid-feedback">
                  {{ validationErrors.description }}
                </div>
              </div>

              <!-- Location -->
              <div class="mb-3">
                <label for="location" class="form-label">Job Location</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="location" 
                  v-model="jobForm.location"
                  :class="{'is-invalid': validationErrors.location}"
                  required
                >
                <div v-if="validationErrors.location" class="invalid-feedback">
                  {{ validationErrors.location }}
                </div>
              </div>
<!-- Job Requirements (Optional) -->
              <div class="mb-3">
                <label for="requirements" class="form-label">Job Requirements</label>
                <textarea 
                  class="form-control" 
                  id="requirements" 
                  rows="3" 
                  v-model="jobForm.requirements"
                  placeholder="Optional: List specific job requirements"
                ></textarea>
              </div>

              <!-- Job Type -->
              <div class="mb-3">
                <label for="type" class="form-label">Job Type</label>
                <select 
                  class="form-select" 
                  id="type" 
                  v-model="jobForm.job_type"
                  :class="{'is-invalid': validationErrors.job_type}"
                  required
                >
                  <option value="">Select Job Type</option>
                  <option value="full-time">Full Time</option>
                  <option value="part-time">Part Time</option>
                  <option value="contract">Contract</option>
                  <option value="remote">Remote</option>
                </select>
                <div v-if="validationErrors.job_type" class="invalid-feedback">
                  {{ validationErrors.job_type }}
                </div>
              </div>

              <!-- Salary Range -->
              <div class="mb-3">
                <label for="salary" class="form-label">Salary Range (Optional)</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="salary" 
                  v-model="jobForm.salary"
                  placeholder="e.g. $50,000 - $75,000"
                >
              </div>

              <!-- Application Deadline -->
              <div class="mb-3">
                <label for="application_deadline" class="form-label">Application Deadline</label>
                <input 
                  type="date" 
                  class="form-control" 
                  id="application_deadline" 
                  v-model="jobForm.application_deadline"
                  required
                >
              </div>

              <!-- Job Status -->
              <div class="mb-3">
                <label for="status" class="form-label">Job Status</label>
                <select 
                  class="form-select" 
                  id="status" 
                  v-model="jobForm.status"
                >
                  <option value="active">Active</option>
                  <option value="draft">Draft</option>
                  <option value="closed">Closed</option>
                </select>
              </div>

              <!-- Submit Button -->
              <div class="d-grid">
                <button 
                  type="submit" 
                  class="btn btn-primary" 
                  :disabled="isSubmitting"
                >
                  <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
                  Post Job
                </button>
              </div>
            </form>

            <!-- Submission Status Messages -->
            <div v-if="submissionSuccess" class="alert alert-success mt-3">
              Job posted successfully!
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
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';
import axios from '@/services/apiClient';

export default {
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();

    const jobForm = ref({
      title: '',
      description: '',
      requirements: '',
      salary: '',
      location: '',
      job_type: '',
      status: 'active',
      application_deadline: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0] // 30 days from now
    });


    const validationErrors = ref({});
    const isSubmitting = ref(false);
    const submissionSuccess = ref(false);
    const submissionError = ref(null);

    const submitJob = async () => {
      // Reset previous states
      validationErrors.value = {};
      submissionSuccess.value = false;
      submissionError.value = null;
      isSubmitting.value = true;

      try {
        // Check authentication and token
        const token = localStorage.getItem('user_token');
        if (!token) {
          throw new Error('Authentication token is missing. Please log in again.');
        }

        // Log diagnostic information
        console.log('Posting Job with:', {
          token: token ? 'Token Present' : 'No Token',
          user: JSON.parse(localStorage.getItem('user_info')),
          employer: await authStore.getCurrentEmployer()
        });

        // Get the current employer
        const employer = await authStore.getCurrentEmployer();
        
        if (!employer) {
          throw new Error('You must be logged in as an employer and have a complete employer profile to post a job.');
        }

        // Prepare job data matching the backend validation
        const jobData = {
          employer_id: employer.id,
          title: jobForm.value.title,
          description: jobForm.value.description,
          requirements: jobForm.value.requirements,
          salary: jobForm.value.salary ? parseFloat(jobForm.value.salary.replace(/[^0-9.-]+/g,"")) : null,
          location: jobForm.value.location,
          job_type: jobForm.value.job_type,
          application_deadline: jobForm.value.application_deadline,
          status: jobForm.value.status
        };

        const response = await axios.post('/v1/jobs', jobData);
        
        submissionSuccess.value = true;
        jobForm.value = {
          title: '',
          description: '',
          requirements: '',
          salary: '',
          location: '',
          job_type: '',
          status: 'active',
          application_deadline: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]
        };

        // Optional: Navigate to job listings or show success message
        router.push('/jobs');
      } catch (error) {
        console.error('Job Submission Error:', error);
        
        // Detailed error handling
        if (error.response) {
          // Server responded with an error
          switch (error.response.status) {
            case 401:
              submissionError.value = 'Authentication failed. Please log in again.';
              authStore.logout();
              router.push('/login');
              break;
            case 403:
              submissionError.value = 'You are not authorized to post this job.';
              break;
            case 422:
              // Validation errors
              validationErrors.value = error.response.data.errors || {};
              submissionError.value = 'Please correct the form errors.';
              break;
            default:
              submissionError.value = error.response.data.message || 'An unexpected error occurred.';
          }
        } else if (error.request) {
          // Request made but no response received
          submissionError.value = 'No response from server. Please check your network connection.';
        } else {
          // Something else went wrong
          submissionError.value = error.message || 'An unexpected error occurred.';
        }
      } finally {
        isSubmitting.value = false;
      }
    };


    return {
      jobForm,
      validationErrors,
      isSubmitting,
      submissionSuccess,
      submissionError,
      submitJob
    };
  }
}
</script>

<style scoped>
.card-header {
  background-color: #007bff !important;
}

.form-control:focus, 
.form-select:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>