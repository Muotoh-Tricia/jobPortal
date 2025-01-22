import axios from 'axios';

// Create an axios instance with default config
const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  timeout: 10000 // 10 seconds timeout
});

// Error handler helper function
const handleError = (error, customMessage) => {
  console.error(customMessage, error);
  if (error.response) {
    // The request was made and the server responded with a status code
    // that falls out of the range of 2xx
    throw new Error(error.response.data.message || customMessage);
  } else if (error.request) {
    // The request was made but no response was received
    throw new Error('No response received from server');
  } else {
    // Something happened in setting up the request that triggered an Error
    throw new Error(error.message || customMessage);
  }
};

export default {
  // Get all jobs
  async getAllJobs() {
    try {
      const response = await apiClient.get('/jobs');
      return response.data.data; // Access the data property of the response
    } catch (error) {
      handleError(error, 'Failed to fetch jobs');
    }
  },

  // Get a specific job
  async getJob(id) {
    try {
      const response = await apiClient.get(`/jobs/${id}`);
      return response.data.data;
    } catch (error) {
      handleError(error, 'Failed to fetch job details');
    }
  },

  // Create a new job
  async createJob(jobData) {
    try {
      const response = await apiClient.post('/jobs', jobData);
      return response.data.data;
    } catch (error) {
      handleError(error, 'Failed to create job');
    }
  },

  // Update a job
  async updateJob(id, jobData) {
    try {
      const response = await apiClient.put(`/jobs/${id}`, jobData);
      return response.data.data;
    } catch (error) {
      handleError(error, 'Failed to update job');
    }
  },

  // Delete a job
  async deleteJob(id) {
    try {
      const response = await apiClient.delete(`/jobs/${id}`);
      return response.data;
    } catch (error) {
      handleError(error, 'Failed to delete job');
    }
  },

  // Search jobs
  async searchJobs(params) {
    try {
      const response = await apiClient.get('/jobs/search', { params });
      return response.data.data;
    } catch (error) {
      handleError(error, 'Failed to search jobs');
    }
  }
};
