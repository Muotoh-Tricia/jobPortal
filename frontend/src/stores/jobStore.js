import { defineStore } from 'pinia';
import jobService from '@/services/jobService';

export const useJobStore = defineStore('jobs', {
  state: () => ({
    jobs: [],
    currentJob: null,
    loading: false,
    error: null
  }),

  getters: {
    allJobs: (state) => state.jobs,
    isLoading: (state) => state.loading,
    hasError: (state) => state.error,
    getJobById: (state) => (id) => state.jobs.find(job => job.id === id),
    filteredJobs: (state) => (keyword = '') => {
      if (!keyword) return state.jobs;
      const searchTerm = keyword.toLowerCase();
      return state.jobs.filter(job => 
        job.companyName.toLowerCase().includes(searchTerm) ||
        job.Description.toLowerCase().includes(searchTerm) ||
        job.Location.toLowerCase().includes(searchTerm)
      );
    }
  },

  actions: {
    async fetchJobs() {
      this.loading = true;
      try {
        const response = await jobService.getAllJobs();
        this.jobs = response.data;
        this.error = null;
      } catch (error) {
        this.error = error.message;
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async fetchJob(id) {
      this.loading = true;
      try {
        const response = await jobService.getJob(id);
        this.currentJob = response.data;
        this.error = null;
        return response.data;
      } catch (error) {
        this.error = error.message;
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async createJob(jobData) {
      this.loading = true;
      try {
        const response = await jobService.createJob(jobData);
        this.jobs.unshift(response.data);
        this.error = null;
        return response.data;
      } catch (error) {
        this.error = error.message;
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async updateJob({ id, jobData }) {
      this.loading = true;
      try {
        const response = await jobService.updateJob(id, jobData);
        const index = this.jobs.findIndex(job => job.id === id);
        if (index !== -1) {
          this.jobs[index] = response.data;
        }
        this.error = null;
        return response.data;
      } catch (error) {
        this.error = error.message;
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async deleteJob(id) {
      this.loading = true;
      try {
        await jobService.deleteJob(id);
        this.jobs = this.jobs.filter(job => job.id !== id);
        this.error = null;
      } catch (error) {
        this.error = error.message;
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async searchJobs(searchParams) {
      this.loading = true;
      try {
        const response = await jobService.searchJobs(searchParams);
        
        // Ensure we use the data property from the response
        this.jobs = response.data || [];
        
        this.error = this.jobs.length === 0 
          ? 'No jobs found matching your search criteria.' 
          : null;
        
        return this.jobs;
      } catch (error) {
        console.error('Job search error:', error);
        this.error = error.message || 'Unable to perform job search.';
        
        // Fallback to all jobs
        await this.fetchJobs();
        
        throw error;
      } finally {
        this.loading = false;
      }
    }
  }
});
