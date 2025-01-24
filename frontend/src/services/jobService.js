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
      const response = await apiClient.get('/v1/jobs');
      return { data: response.data }; // Wrap response to match existing store logic
    } catch (error) {
      handleError(error, 'Failed to fetch jobs');
    }
  },

  // Get a specific job
  async getJob(id) {
    try {
      const response = await apiClient.get(`/v1/jobs/${id}`);
      return { data: response.data };
    } catch (error) {
      handleError(error, 'Failed to fetch job details');
    }
  },

  // Create a new job
  async createJob(jobData) {
    try {
      const response = await apiClient.post('/v1/jobs', jobData);
      return { data: response.data };
    } catch (error) {
      handleError(error, 'Failed to create job');
    }
  },

  // Update a job
  async updateJob(id, jobData) {
    try {
      const response = await apiClient.put(`/v1/jobs/${id}`, jobData);
      return { data: response.data };
    } catch (error) {
      handleError(error, 'Failed to update job');
    }
  },

  // Delete a job
  async deleteJob(id) {
    try {
      const response = await apiClient.delete(`/v1/jobs/${id}`);
      return response.data;
    } catch (error) {
      handleError(error, 'Failed to delete job');
    }
  },

  // Search jobs
  async searchJobs(params) {
    try {
      // Clean and validate search parameters
      const cleanParams = {};
      
      // Add parameters only if they are not empty
      if (params.query && params.query.trim() !== '') {
        cleanParams.query = params.query.trim();
      }
      
      if (params.location && params.location.trim() !== '') {
        cleanParams.location = params.location.trim();
      }
      
      if (params.job_type && params.job_type.trim() !== '') {
        cleanParams.job_type = params.job_type.trim();
      }

      // If no parameters are provided, return all jobs
      if (Object.keys(cleanParams).length === 0) {
        return await this.getAllJobs();
      }

      try {
        const response = await apiClient.get('/v1/jobs/search', { 
          params: cleanParams,
          validateStatus: function (status) {
            return status >= 200 && status < 300;
          }
        });
        
    
        // Ensure response data is an array
        const searchResults = Array.isArray(response.data) ? response.data : [];
        
        // Validate and transform job data
        const validatedResults = searchResults.map(job => ({
          id: job.id || null,
          title: job.title || 'Untitled Job',
          description: job.description || 'No description available',
          location: job.location || 'Location not specified',
          type: job.job_type || 'Not specified',
          companyName: job.employer?.name || 'Unknown Company',
          companyLogo: job.employer?.logo || null,
          salary: job.salary || 'Not disclosed',
          level: job.level || 'Entry Level'
        }));

        return { data: validatedResults };
      } catch (axiosError) {
        // More detailed error logging
        console.error('Axios Search Error:', {
          status: axiosError.response?.status,
          data: axiosError.response?.data,
          headers: axiosError.response?.headers,
          config: axiosError.config
        });

        // If it's a 422 error, log the validation errors
        if (axiosError.response?.status === 422) {
          console.error('Validation Errors:', axiosError.response.data.errors);
        }

        // Fallback to all jobs
        return await this.getAllJobs();
      }
    } catch (error) {
      // Catch any other unexpected errors
      console.error('Unexpected search error:', error);
      return await this.getAllJobs();
    }
  }
};
