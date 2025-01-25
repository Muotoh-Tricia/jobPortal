<template>
  <div class="profile-container container mt-5">
    <div v-if="!currentUser" class="text-center">
      <p>Loading user information...</p>
    </div>
    
    <div v-else class="row">
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-body text-center">
            <img 
              :src="profileImage" 
              class="rounded-circle img-fluid profile-avatar" 
              alt="Profile Picture"
            >
            <h4 class="mt-3">{{ currentUser.name }}</h4>
            <p class="text-muted">{{ currentUser.role === 'jobseeker' ? 'Job Seeker' : 'Employer' }}</p>
            <button @click="editProfile" class="btn btn-primary mt-2">Edit Profile</button>
          </div>
        </div>
      </div>
      
      <div class="col-md-8">
        <!-- Job Seeker Dashboard -->
        <div v-if="currentUser.role === 'jobseeker'" class="card">
          <div class="card-header">
            <h3>Job Seeker Dashboard</h3>
          </div>
          <div class="card-body">
            <h5>My Applications</h5>
            <div v-if="applications.length === 0" class="alert alert-info">
              You haven't applied to any jobs yet.
            </div>
            <table v-else class="table table-striped">
              <thead>
                <tr>
                  <th>Job Title</th>
                  <th>Company</th>
                  <th>Status</th>
                  <th>Applied Date</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="app in applications" :key="app.id">
                  <td>{{ app.job_title }}</td>
                  <td>{{ app.company_name }}</td>
                  <td>
                    <span 
                      :class="{
                        'badge': true,
                        'bg-warning': app.status === 'pending',
                        'bg-success': app.status === 'accepted',
                        'bg-danger': app.status === 'rejected'
                      }"
                    >
                      {{ app.status }}
                    </span>
                  </td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Employer Dashboard -->
        <div v-else-if="currentUser.role === 'employer'" class="card">
          <div class="card-header">
            <h3>Employer Dashboard</h3>
          </div>
          <div class="card-body">
            <h5>Job Postings</h5>
            <div v-if="jobPostings.length === 0" class="alert alert-info">
              You haven't posted any jobs yet.
            </div>
            <table v-else class="table table-striped">
              <thead>
                <tr>
                  <th>Job Title</th>
                  <th>Applications</th>
                  <th>Status</th>
                  <th>Posted Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="job in jobPostings" :key="job.id">
                  <td>{{ job.title }}</td>
                  <td>{{ job.application_count }}</td>
                  <td>
                    <span 
                      :class="{
                        'badge': true,
                        'bg-primary': job.status === 'active',
                        'bg-secondary': job.status === 'closed'
                      }"
                    >
                      {{ job.status }}
                    </span>
                  </td>
                  <td>{{ formatDate(job.created_at) }}</td>
                  <td>
                    <button @click="viewJobApplications(job.id)" class="btn btn-sm btn-info me-1">
                      View Applications
                    </button>
                    <button @click="editJob(job.id)" class="btn btn-sm btn-warning">
                      Edit
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '@/stores/authStore';
import apiClient from '@/services/apiClient';

export default {
  name: 'ProfileDashboard',
  setup() {
    const authStore = useAuthStore();
    return { authStore };
  },
  data() {
    return {
      currentUser: null,
      applications: [],
      jobPostings: [],
      profileImage: 'https://via.placeholder.com/150'
    }
  },
  async created() {
    try {
      // Get current user from auth store
      this.currentUser = this.authStore.user;
      
      if (!this.currentUser) {
        this.$router.push('/login');
        return;
      }

      // If employer, fetch their job postings
      if (this.currentUser.role === 'employer') {
        await this.fetchEmployerJobs();
      } else if (this.currentUser.role === 'jobseeker') {
        await this.fetchJobSeekerApplications();
      }
    } catch (error) {
      console.error('Profile initialization error:', error);
      this.$router.push('/login');
    }
  },
  methods: {
    async fetchEmployerJobs() {
      try {
        // Get the current employer's ID
        const employer = await this.authStore.getCurrentEmployer();
        
        if (!employer) {
          console.warn('No employer profile found');
          return;
        }

        // Fetch jobs for this employer
        const response = await apiClient.get(`/v1/employers/${employer.id}/jobs`);
        
        this.jobPostings = response.data.map(job => ({
          ...job,
          application_count: job.applications_count || 0,
          created_at: job.created_at
        }));
      } catch (error) {
        console.error('Error fetching employer jobs:', error);
        // Optionally show an error message to the user
      }
    },
    async fetchJobSeekerApplications() {
      try {
        const response = await apiClient.get('/v1/job-seeker/applications');
        this.applications = response.data;
      } catch (error) {
        console.error('Error fetching job seeker applications:', error);
      }
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString();
    },
    editProfile() {
      // Implement profile editing logic
      console.log('Edit profile clicked');
    },
    viewJobApplications(jobId) {
      // Navigate to job applications view
      this.$router.push(`/job-applications/${jobId}`);
    },
    editJob(jobId) {
      // Navigate to job editing view
      this.$router.push(`/edit-job/${jobId}`);
    }
  }
}
</script>

<style scoped>
.profile-avatar {
  width: 150px;
  height: 150px;
  object-fit: cover;
}

.card-header {
  background-color: #f8f9fa;
}

.table {
  margin-bottom: 0;
}
</style>
