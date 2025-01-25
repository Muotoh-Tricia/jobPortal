<template>
  <div class="job-details-view">
    
    <main class="main-content">
      <div class="container py-4">
        <!-- Loading State -->
        <div v-if="jobStore.loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>

        <!-- Error State -->
        <div v-else-if="jobStore.error" class="alert alert-danger" role="alert">
          {{ jobStore.error }}
        </div>

        <!-- Job Details -->
        <div v-else-if="job" class="card">
          <div class="card-body">
            <div class="row">
              <!-- Company Info -->
              <div class="col-md-4 mb-4 mb-md-0">
                <div class="company-info">
                  <img
                    v-if="job.employer && job.employer.logo"
                    :src="job.employer.logo"
                    :alt="job.employer.name"
                    class="company-logo mb-3"
                  >
                  <h4 class="company-name">{{ job.employer ? job.employer.name : 'Unknown Company' }}</h4>
                  <p class="text-muted">
                    <i class="bi bi-geo-alt"></i> {{ job.location }}
                  </p>
                  <div class="contact-info" v-if="job.employer">
                    <p><i class="bi bi-envelope"></i> {{ job.employer.email || 'Not available' }}</p>
                    <p><i class="bi bi-telephone"></i> {{ job.employer.phone || 'Not available' }}</p>
                  </div>
                </div>
              </div>

              <!-- Job Info -->
              <div class="col-md-8">
                <div class="job-info">
                  <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                      <h2 class="job-title mb-2">{{ job.title }}</h2>
                      <div class="job-meta">
                        <span class="badge bg-primary me-2">{{ job.type }}</span>
                        <span class="badge bg-secondary me-2">{{ job.level || 'Entry Level' }}</span>
                        <span class="badge bg-info">{{ formatSalary(job.salary) }}</span>
                      </div>
                    </div>
                    <button 
                      @click="applyForJob" 
                      class="btn btn-primary"
                    >
                      Apply Now
                    </button>
                  </div>

                  <div class="job-description mb-4">
                    <h5>Job Description</h5>
                    <p>{{ job.description }}</p>
                  </div>

                  <div class="job-responsibilities mb-4">
                    <h5>Responsibilities</h5>
                    <div v-html="formatResponsibilities(job.requirements)"></div>
                  </div>

                  <div class="additional-info">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <h6>Job Type</h6>
                        <p>{{ job.type || 'Not specified' }}</p>
                      </div>
                      <div class="col-md-6 mb-3">
                        <h6>Location</h6>
                        <p>{{ job.location }}</p>
                      </div>
                      <div class="col-md-6 mb-3">
                        <h6>Salary</h6>
                        <p>{{ formatSalary(job.salary) }}</p>
                      </div>
                      <div class="col-md-6 mb-3">
                        <h6>Status</h6>
                        <p>{{ job.status || 'Active' }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Job Not Found -->
        <div v-else class="text-center py-5">
          <h3>Job not found</h3>
          <p>The job you're looking for might have been removed or doesn't exist.</p>
          <router-link to="/jobs" class="btn btn-primary mt-3">
            Back to Jobs
          </router-link>
        </div>
      </div>
    </main>

    <!-- Job Application Modal -->
    <div 
      class="modal fade" 
      id="jobApplicationModal" 
      ref="jobApplicationModal"
      tabindex="-1" 
      aria-labelledby="jobApplicationModalLabel" 
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="jobApplicationModalLabel">
              Apply for {{ job ? job.title : 'Job' }}
            </h5>
            <button 
              type="button" 
              class="btn-close" 
              data-bs-dismiss="modal" 
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="submitApplication">
              <!-- Personal Information -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="name" class="form-label">Full Name</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="name" 
                    v-model="applicationForm.name" 
                    required
                  >
                </div>
                <div class="col-md-6 mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    v-model="applicationForm.email" 
                    required
                  >
                </div>
              </div>

              <!-- Contact Information -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="phone" class="form-label">Phone Number</label>
                  <input 
                    type="tel" 
                    class="form-control" 
                    id="phone" 
                    v-model="applicationForm.phone" 
                    required
                  >
                </div>
                <div class="col-md-6 mb-3">
                  <label for="location" class="form-label">Location</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    id="location" 
                    v-model="applicationForm.location" 
                    required
                  >
                </div>
              </div>

              <!-- Cover Letter -->
              <div class="mb-3">
                <label for="coverLetter" class="form-label">Cover Letter (Optional)</label>
                <textarea 
                  class="form-control" 
                  id="coverLetter" 
                  rows="4" 
                  v-model="applicationForm.cover_letter"
                ></textarea>
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
                <small class="text-muted">PDF, DOC, DOCX (Max 5MB)</small>
              </div>

              <!-- Submit Button -->
              <div class="d-grid">
                <button 
                  type="submit" 
                  class="btn btn-primary" 
                  :disabled="isSubmitting"
                >
                  <span 
                    v-if="isSubmitting" 
                    class="spinner-border spinner-border-sm me-2"
                  ></span>
                  Submit Application
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { useJobStore } from '@/stores/jobStore';
import { useAuthStore } from '@/stores/authStore';
import apiClient from '@/services/apiClient';
import { Modal } from 'bootstrap';
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';

export default {
  name: 'JobDetailsView',
  setup() {
    const jobStore = useJobStore();
    const authStore = useAuthStore();
    const router = useRouter();
    return { jobStore, authStore, router };
  },
  data() {
    return {
      pageTitle: 'Job Details',
      jobApplicationModal: null,
      applicationForm: {
        name: '',
        email: '',
        phone: '',
        location: '',
        cover_letter: '',
        resume: null
      },
      isSubmitting: false
    };
  },
  computed: {
    job() {
      return this.jobStore.currentJob;
    }
  },
  mounted() {
    document.title = `${this.pageTitle} - ${this.$route.params.id}`;
    this.fetchJobDetails();

    // Initialize modal
    this.jobApplicationModal = new Modal(this.$refs.jobApplicationModal);

    // Check if application modal should be opened
    this.$nextTick(() => {
      if (this.$route.query.applyNow === 'true') {
        this.openJobApplicationModal();
      }
    });

    // Pre-fill user details if logged in
    const currentUser = this.authStore.user;
    if (currentUser) {
      this.applicationForm.name = currentUser.name;
      this.applicationForm.email = currentUser.email;
    }
  },
  methods: {
    formatSalary(salary) {
      if (salary == null) return 'Not specified';
      
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0
      }).format(salary);
    },

    formatDate(date) {
      if (!date) return 'Not specified';
      return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },

    formatResponsibilities(responsibilities) {
      if (!responsibilities) return '<p>Not specified</p>';
      
      // If responsibilities is already an array, use it; otherwise, split by newlines
      const items = Array.isArray(responsibilities) 
        ? responsibilities 
        : (responsibilities.split('\n') || []);

      return items
        .filter(item => item.trim())
        .map(item => `<p>• ${item.trim()}</p>`)
        .join('');
    },

    applyForJob() {
      if (this.job) {
        this.jobApplicationModal.show();
      }
    },

    openJobApplicationModal() {
      // Pre-fill form with user details
      const currentUser = this.authStore.user;
      this.applicationForm.name = currentUser.name;
      this.applicationForm.email = currentUser.email;

      // Open the modal
      if (this.jobApplicationModal) {
        this.jobApplicationModal.show();
      }

      // Remove the query parameter to prevent reopening on refresh
      this.$router.replace({ 
        name: 'job-details', 
        params: { id: this.job.id } 
      });
    },

    async submitApplication() {
      this.isSubmitting = true;

      // Validate form fields
      const requiredFields = ['name', 'email', 'phone', 'location'];
      const missingFields = requiredFields.filter(field => 
        !this.applicationForm[field] || this.applicationForm[field].trim() === ''
      );

      if (missingFields.length > 0) {
        Swal.fire({
          icon: 'error',
          title: 'Missing Information',
          text: `Please fill in the following fields: ${missingFields.join(', ')}`
        });
        this.isSubmitting = false;
        return;
      }

      try {
        const formData = new FormData();
        
        // Required fields
        formData.append('job_id', this.job.id);
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

        // Debug: Log form data contents
        for (let [key, value] of formData.entries()) {
          console.log(`Form Data - ${key}:`, value);
        }

        // Submit application
        const response = await apiClient.post('/v1/applications', formData);

        Swal.fire({
          icon: 'success',
          title: 'Application Submitted',
          text: 'Your job application has been successfully submitted!'
        });

        // Close modal
        this.jobApplicationModal.hide();
      } catch (error) {
        console.error('Application submission error:', {
          message: error.message,
          response: error.response?.data,
          status: error.response?.status
        });
        
        // Handle specific error scenarios
        if (error.response) {
          const errorData = error.response.data;
          
          // Check for duplicate application
          if (errorData.error === 'You have already applied for this job') {
            Swal.fire({
              icon: 'warning',
              title: 'Duplicate Application',
              html: `
                <p>You have already submitted an application for this job.</p>
                <p>Would you like to view your existing applications?</p>
              `,
              showCancelButton: true,
              confirmButtonText: 'View Applications',
              cancelButtonText: 'Close'
            }).then((result) => {
              if (result.isConfirmed) {
                // Navigate to user's applications page
                this.$router.push('/applications');
              }
            });
          } 
          // Handle validation errors
          else if (errorData.errors) {
            const errorMessages = Object.entries(errorData.errors)
              .map(([field, messages]) => `• ${field}: ${messages.join(', ')}`)
              .join('\n');

            Swal.fire({
              icon: 'error',
              title: 'Submission Failed',
              html: `<p>Please correct the following errors:</p><pre>${errorMessages}</pre>`
            });
          }
          // Generic error handling
          else {
            Swal.fire({
              icon: 'error',
              title: 'Submission Failed',
              text: errorData.message || 'An unexpected error occurred'
            });
          }
        } else {
          // Network or other errors
          Swal.fire({
            icon: 'error',
            title: 'Submission Failed',
            text: 'Unable to submit application. Please check your network connection.'
          });
        }
      } finally {
        this.isSubmitting = false;
      }
    },

    handleResumeUpload(event) {
      const file = event.target.files[0];
      
      // Basic file validation
      if (file) {
        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = [
          'application/pdf', 
          'application/msword', 
          'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (file.size > maxSize) {
          Swal.fire({
            icon: 'warning',
            title: 'File Too Large',
            text: 'Resume must be less than 5MB'
          });
          event.target.value = null;
          return;
        }

        if (!allowedTypes.includes(file.type)) {
          Swal.fire({
            icon: 'warning',
            title: 'Invalid File Type',
            text: 'Please upload PDF or Word documents'
          });
          event.target.value = null;
          return;
        }

        this.applicationForm.resume = file;
      }
    },

    async fetchJobDetails() {
      try {
        const jobId = this.$route.params.id;
        await this.jobStore.fetchJob(jobId);
      } catch (error) {
        console.error('Error fetching job details:', error);
        this.$router.push('/jobs');
      }
    }
  }
}
</script>

<style scoped>
.job-details-view {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  background-color: #f8f9fa;
}

.company-logo {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.company-info {
  padding: 1.5rem;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.contact-info p {
  margin-bottom: 0.5rem;
}

.contact-info i {
  width: 20px;
  color: #6c757d;
}

.job-title {
  color: #2c3e50;
  font-weight: 600;
}

.job-meta {
  margin: 1rem 0;
}

.badge {
  padding: 0.5em 1em;
  font-weight: 500;
}

.job-description,
.job-responsibilities,
.additional-info {
  background-color: #fff;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  margin-bottom: 1.5rem;
}

h5 {
  color: #2c3e50;
  font-weight: 600;
  margin-bottom: 1rem;
}

h6 {
  color: #6c757d;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.btn-primary {
  padding: 0.5rem 2rem;
  font-weight: 500;
}

@media (max-width: 768px) {
  .company-info {
    text-align: center;
  }

  .job-meta {
    margin: 0.5rem 0;
  }

  .badge {
    margin-bottom: 0.5rem;
  }
}
</style>
