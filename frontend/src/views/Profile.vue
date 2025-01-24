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
import authService from '@/services/authService';

export default {
  name: 'ProfileDashboard',
  data() {
    return {
      currentUser: null,
      applications: [],
      jobPostings: [],
      profileImage: 'https://via.placeholder.com/150'
    }
  },
  created() {
    this.currentUser = authService.getCurrentUser();
    
    if (!this.currentUser) {
      this.$router.push('/login');
    }
  },
  methods: {
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
