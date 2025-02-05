<template>
  <div class="container py-5">
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Job Applications</h4>
            <div class="btn-group">
              <button 
                class="btn btn-light btn-sm" 
                @click="filterStatus = 'all'"
              >
                All
              </button>
              <button 
                class="btn btn-light btn-sm" 
                @click="filterStatus = 'pending'"
              >
                Pending
              </button>
              <button 
                class="btn btn-light btn-sm" 
                @click="filterStatus = 'accepted'"
              >
                Accepted
              </button>
              <button 
                class="btn btn-light btn-sm" 
                @click="filterStatus = 'rejected'"
              >
                Rejected
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Job Title</th>
                    <th>Applicant Name</th>
                    <th>Email</th>
                    <th>Applied Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr 
                    v-for="application in filteredApplications" 
                    :key="application.id"
                  >
                    <td>{{ application.job.title }}</td>
                    <td>{{ application.applicant.name }}</td>
                    <td>{{ application.applicant.email }}</td>
                    <td>{{ formatDate(application.created_at) }}</td>
                    <td>
                      <span :class="getStatusBadgeClass(application.status)">
                        {{ application.status }}
                      </span>
                    </td>
                    <td>
                      <div class="btn-group">
                        <button 
                          class="btn btn-sm btn-info" 
                          @click="viewApplicationDetails(application)"
                        >
                          <i class="bi bi-eye"></i> View
                        </button>
                        <button 
                          class="btn btn-sm btn-success" 
                          @click="updateApplicationStatus(application.id, 'accepted')"
                        >
                          <i class="bi bi-check-circle"></i> Accept
                        </button>
                        <button 
                          class="btn btn-sm btn-danger" 
                          @click="updateApplicationStatus(application.id, 'rejected')"
                        >
                          <i class="bi bi-x-circle"></i> Reject
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Application Details Modal -->
    <div class="modal fade" id="applicationDetailsModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Application Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" v-if="selectedApplication">
            <div class="row">
              <div class="col-md-6">
                <h6>Applicant Information</h6>
                <p><strong>Name:</strong> {{ selectedApplication.applicant.name }}</p>
                <p><strong>Email:</strong> {{ selectedApplication.applicant.email }}</p>
                <p><strong>Phone:</strong> {{ selectedApplication.applicant.phone || 'N/A' }}</p>
              </div>
              <div class="col-md-6">
                <h6>Job Details</h6>
                <p><strong>Job Title:</strong> {{ selectedApplication.job.title }}</p>
                <p><strong>Company:</strong> {{ selectedApplication.job.company }}</p>
                <p><strong>Location:</strong> {{ selectedApplication.job.location }}</p>
              </div>
              <div class="col-12 mt-3">
                <h6>Resume</h6>
                <div class="d-grid gap-2">
                  <a 
                    :href="selectedApplication.resume_url" 
                    class="btn btn-outline-primary" 
                    target="_blank"
                  >
                    <i class="bi bi-file-earmark-pdf"></i> View Resume
                  </a>
                </div>
              </div>
              <div class="col-12 mt-3">
                <h6>Cover Letter</h6>
                <p>{{ selectedApplication.cover_letter || 'No cover letter provided' }}</p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button 
              class="btn btn-secondary" 
              data-bs-dismiss="modal"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: 'ViewApplication',
  setup() {
    const applications = ref([])
    const selectedApplication = ref(null)
    const filterStatus = ref('all')

    const filteredApplications = computed(() => {
      if (filterStatus.value === 'all') return applications.value
      return applications.value.filter(app => app.status === filterStatus.value)
    })

    const fetchApplications = async () => {
      try {
        const response = await axios.get('/api/employer/applications')
        applications.value = response.data
      } catch (error) {
        console.error('Error fetching applications:', error)
      }
    }

    const viewApplicationDetails = (application) => {
      selectedApplication.value = application
      const modal = new bootstrap.Modal(document.getElementById('applicationDetailsModal'))
      modal.show()
    }

    const updateApplicationStatus = async (applicationId, status) => {
      try {
        await axios.put(`/api/applications/${applicationId}/status`, { status })
        await fetchApplications()
      } catch (error) {
        console.error('Error updating application status:', error)
      }
    }

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString()
    }

    const getStatusBadgeClass = (status) => {
      const classes = {
        pending: 'badge bg-warning',
        accepted: 'badge bg-success',
        rejected: 'badge bg-danger',
        reviewed: 'badge bg-info'
      }
      return classes[status] || 'badge bg-secondary'
    }

    onMounted(fetchApplications)

    return {
      applications,
      selectedApplication,
      filterStatus,
      filteredApplications,
      viewApplicationDetails,
      updateApplicationStatus,
      formatDate,
      getStatusBadgeClass
    }
  }
}
</script>

<style scoped>
.table th {
  font-weight: 600;
}

.badge {
  font-size: 0.85em;
}
</style>