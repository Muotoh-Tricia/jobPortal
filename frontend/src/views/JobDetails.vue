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
                    <button class="btn btn-primary" @click="applyForJob">
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

  </div>
</template>

<script>
import { useJobStore } from '@/stores/jobStore';

export default {
  name: 'JobDetailsView',

  data() {
    return {
      jobStore: useJobStore(),
      pageTitle: 'Job Details'
    };
  },

  computed: {
    job() {
      return this.jobStore.currentJob;
    }
  },

  methods: {
    formatSalary(salary) {
      // Handle undefined or null values
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

    async applyForJob() {
      // This will be implemented when we create the job application functionality
      if (!this.job) {
        console.error('No job selected');
        return;
      }
      console.log('Applying for job:', this.job.id);
    },

    async fetchJobDetails() {
      try {
        const jobId = this.$route.params.id;
        if (!jobId) {
          throw new Error('No job ID provided');
        }
        
        await this.jobStore.fetchJob(jobId);
      } catch (error) {
        console.error('Error fetching job details:', error);
        // Optionally, you can set an error state or redirect
        this.$router.push('/jobs');
      }
    }
  },

  mounted() {
    document.title = `${this.pageTitle} - ${this.$route.params.id}`;
    this.fetchJobDetails();
  }
};
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
