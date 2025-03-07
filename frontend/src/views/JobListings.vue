<template>
  <div class="job-listings-view">
    
    <main class="main-content">
      <!-- Search and Filter Section -->
      <section class="search-filters mb-4">
        <div class="container">
          <div class="row g-3">
            <div class="col-md-4">
              <input
                v-model="searchQuery"
                type="text"
                class="form-control"
                placeholder="Search jobs..."
                @input="handleSearch"
              >
            </div>
            <div class="col-md-3">
              <select v-model="selectedLocation" class="form-select" @change="handleSearch">
                <option value="">All Locations</option>
                <option v-for="location in uniqueLocations" :key="location" :value="location">
                  {{ location }}
                </option>
              </select>
            </div>
            <div class="col-md-3">
              <select v-model="selectedJobType" class="form-select" @change="handleSearch">
                <option value="">All Job Types</option>
                <option value="Full-time">Full-time</option>
                <option value="Part-time">Part-time</option>
                <option value="Contract">Contract</option>
                <option value="Internship">Internship</option>
              </select>
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary w-100" @click="handleSearch">
                Search
              </button>
              <button class="btn btn-secondary w-100 mt-2" @click="resetSearch">
                Reset
              </button>
            </div>
          </div>
        </div>
      </section>

      <!-- Jobs Section -->
      <section class="jobs-section">
        <div class="container">
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

          <!-- Jobs List -->
          <div v-else>
            <div v-if="jobs.length === 0 && isSearchPerformed" class="text-center py-5">
              <h3>{{ searchResultMessage }}</h3>
              <p>Try adjusting your search criteria</p>
            </div>
            
            <div v-else class="row">
              <div v-for="job in jobs" :key="job.id" class="col-12 mb-4">
                <div class="card job-card">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <img
                        v-if="job.companyLogo"
                        :src="job.companyLogo"
                        :alt="job.companyName"
                        class="company-logo me-3"
                      >
                      <div>
                        <h5 class="card-title mb-1">{{ job.title }}</h5>
                        <p class="text-muted mb-0">{{ job.location }}</p>
                      </div>
                    </div>
                    
                    <div class="job-details">
                      <p class="card-text">{{ truncateDescription(job.description) }}</p>
                      <div class="job-meta">
                        <span class="badge bg-primary me-2">{{ job.type }}</span>
                        <span class="badge bg-secondary me-2">{{ job.level || 'Entry Level' }}</span>
                        <span class="badge bg-info">{{ formatSalary(job.salary) }}</span>
                      </div>
                    </div>

                    <div class="mt-3">
                      <router-link
                        :to="{ name: 'job-details', params: { id: job.id }}"
                        class="btn btn-outline-primary"
                      >
                        View Details
                      </router-link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Job Application Modal -->
    <div 
      class="modal fade" 
      id="jobApplicationModal" 
      tabindex="-1" 
      aria-labelledby="jobApplicationModalLabel" 
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="jobApplicationModalLabel">
              Apply for {{ selectedJob?.title }}
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
import apiClient from '@/services/apiClient';
import { useAuthStore } from '@/stores/authStore';
import { Modal } from 'bootstrap';
import Swal from 'sweetalert2';

export default {
  name: 'JobListingsView',

  data() {
    return {
      jobStore: useJobStore(),
      searchQuery: '',
      selectedLocation: '',
      selectedJobType: '',
      pageTitle: 'Job Listings',
      searchTimeout: null,
      isSearchPerformed: false,
      selectedJob: null,
      applicationForm: {
        name: '',
        email: '',
        phone: '',
        location: '',
        cover_letter: '',
        resume: null
      },
      isSubmitting: false,
      jobApplicationModal: null
    };
  },

  computed: {
    jobs() {
      return this.jobStore.allJobs || [];
    },
    uniqueLocations() {
      try {
        // Safely get unique locations with fallback
        const locations = (this.jobs || [])
          .map(job => job.location)
          .filter(location => location && location.trim() !== '');
        return [...new Set(locations)];
      } catch (error) {
        console.error('Error computing unique locations:', error);
        return [];
      }
    },
    searchResultMessage() {
      if (!this.isSearchPerformed) return '';

      const jobCount = this.jobs ? this.jobs.length : 0;
      if (jobCount > 0) {
        return `Found ${jobCount} job${jobCount !== 1 ? 's' : ''}`;
      }

      return 'No jobs found matching your search criteria';
    }
  },

  methods: {
    handleSearch() {
      // Clear any existing timeout
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }

      // Set flag that search has been performed
      this.isSearchPerformed = true;

      // Reset any previous errors
      this.jobStore.error = null;

      // Trim search inputs to prevent unnecessary whitespace searches
      const trimmedQuery = this.searchQuery ? this.searchQuery.trim() : '';
      const trimmedLocation = this.selectedLocation ? this.selectedLocation.trim() : '';
      const trimmedJobType = this.selectedJobType ? this.selectedJobType.trim() : '';

      // Validate search inputs
      const searchParams = {};
      if (trimmedQuery) searchParams.query = trimmedQuery;
      if (trimmedLocation) searchParams.location = trimmedLocation;
      if (trimmedJobType) searchParams.job_type = trimmedJobType;

      // Only perform search if at least one field has a value
      if (Object.keys(searchParams).length > 0) {
        // Set a new timeout
        this.searchTimeout = setTimeout(async () => {
          try {
            const searchResults = await this.jobStore.searchJobs(searchParams);

            if (!searchResults || searchResults.length === 0) {
              this.jobStore.error = 'No jobs found matching your search criteria.';
            }
          } catch (error) {
            console.error('Error searching jobs:', error);

            this.jobStore.error = 'Unable to perform search. Please try again later.';
          }
        }, 300); 
      } else {
        this.fetchJobs();
      }
    },

    resetSearch() {
      this.searchQuery = '';
      this.selectedLocation = '';
      this.selectedJobType = '';
      this.isSearchPerformed = false;
      this.fetchJobs();
    },

    truncateDescription(text, length = 150) {
      if (!text) return '';
      
      const safeText = String(text);
      
      if (safeText.length <= length) return safeText;
      return safeText.substring(0, length) + '...';
    },

    formatSalary(salary) {
      if (salary == null) return 'Not specified';
      
      return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 0
      }).format(salary);
    },

    fetchJobs() {
      try {
        this.jobStore.fetchJobs();
      } catch (error) {
        console.error('Error fetching jobs:', error);
        this.jobStore.error = 'Unable to load jobs. Please try again later.';
      }
    },
    async checkJobEligibility(job) {
      // Check if user is authenticated
      const token = localStorage.getItem('user_token');
      if (!token) {
        Swal.fire({
          icon: 'warning',
          title: 'Login Required',
          text: 'Please log in to apply for this job',
          confirmButtonText: 'Go to Login',
          showCancelButton: true
        }).then((result) => {
          if (result.isConfirmed) {
            this.$router.push('/login');
          }
        });
        return;
      }

      try {
        console.group('Job Eligibility Check');
        console.log('Job Details:', job);
        console.log('Authentication Token:', token ? 'Present' : 'Missing');

        // Verify job eligibility
        const fullUrl = `/api/v1/jobs/${job.id}/check-eligibility`;
        console.log('Full API URL:', fullUrl);

        const response = await apiClient.get(fullUrl, {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
          }
        });

        console.log('Full Response:', response);
        console.log('Response Data:', response.data);
        console.groupEnd();

        if (!response.data.eligible) {
          Swal.fire({
            icon: 'warning',
            title: 'Application Not Possible',
            text: response.data.message || 'You are not eligible to apply for this job.'
          });
          return;
        }

        // Set the selected job for the modal
        this.selectedJob = job;
        
        // Populate application form with initial data
        this.applicationForm = {
          job_id: job.id,
          name: this.currentUser?.name || '',
          email: this.currentUser?.email || '',
          phone: this.currentUser?.phone || '',
          location: this.currentUser?.location || job.location,
          cover_letter: '',
          resume: null
        };

        // Open the modal using Bootstrap's modal method
        const jobApplicationModal = new bootstrap.Modal(document.getElementById('jobApplicationModal'));
        jobApplicationModal.show();

      } catch (error) {
        console.group('Eligibility Check Error');
        console.error('Full Error Object:', error);
        
        if (error.response) {
          console.error('Response Error Details:', {
            status: error.response.status,
            data: error.response.data,
            headers: error.response.headers,
            config: error.config
          });

          const errorMessage = 
            error.response.data.message || 
            error.response.data.error || 
            'Unable to check job eligibility. Please try again.';
          
          Swal.fire({
            icon: 'error',
            title: 'Application Error',
            text: errorMessage,
            footer: `Error Code: ${error.response.status}`
          });
        } else if (error.request) {
          console.error('Request Error:', error.request);
          Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'No response received from server. Please check your connection.'
          });
        } else {
          console.error('Unexpected Error:', error.message);
          Swal.fire({
            icon: 'error',
            title: 'Unexpected Error',
            text: 'An unexpected error occurred. Please try again.'
          });
        }
        console.groupEnd();
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
      this.isSubmitting = true;

      try {
        const formData = new FormData();
        formData.append('job_id', this.selectedJob.id);
        formData.append('name', this.applicationForm.name);
        formData.append('email', this.applicationForm.email);
        formData.append('phone', this.applicationForm.phone);
        formData.append('location', this.applicationForm.location);
        
        if (this.applicationForm.cover_letter) {
          formData.append('cover_letter', this.applicationForm.cover_letter);
        }

        if (this.applicationForm.resume) {
          formData.append('resume', this.applicationForm.resume);
        }

        await apiClient.post('/v1/applications', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });

        // Close modal and reset form
        this.jobApplicationModal.hide();
        this.resetApplicationForm();
        alert('Application submitted successfully!');
      } catch (error) {
        console.error('Application submission error:', error);
        alert(error.response?.data?.message || 'Failed to submit application');
      } finally {
        this.isSubmitting = false;
      }
    },
    resetApplicationForm() {
      this.applicationForm = {
        name: '',
        email: '',
        phone: '',
        location: '',
        cover_letter: '',
        resume: null
      };
    }
  },

  mounted() {
    document.title = this.pageTitle;
    this.fetchJobs();
    this.jobApplicationModal = new Modal(document.getElementById('jobApplicationModal'));
  },

  beforeUnmount() {
    if (this.searchTimeout) {
      clearTimeout(this.searchTimeout);
    }
  }
};
</script>

<style scoped>
.job-listings-view {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  padding: 2rem 0;
  background-color: #f8f9fa;
}

.search-filters {
  background-color: #ffffff;
  padding: 1.5rem 0;
  margin-bottom: 2rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.jobs-section {
  background-color: transparent;
}

.company-logo {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
}

.job-card {
  transition: transform 0.2s ease-in-out;
  border: 1px solid #dee2e6;
  background-color: #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.job-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.job-meta {
  margin-top: 1rem;
}

.badge {
  padding: 0.5em 1em;
  font-weight: 500;
}

.card-title {
  color: #2c3e50;
  font-weight: 600;
}

.btn-outline-primary {
  border-width: 2px;
}

.btn-outline-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
  .main-content {
    padding: 1rem 0;
  }

  .search-filters {
    padding: 1rem 0;
  }

  .job-card {
    margin-bottom: 1rem;
  }
}
</style>