import { defineStore } from 'pinia';
import axios from '@/services/apiClient';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user_info')) || null,
    token: localStorage.getItem('user_token') || null,
    employer: null,
    jobSeeker: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    currentUser: (state) => state.user,
    isEmployer: (state) => !!state.employer,
    isJobSeeker: (state) => !!state.jobSeeker
  },

  actions: {
    async login(credentials) {
      try {
        const response = await axios.post('/v1/auth/login', credentials);
        
        // Validate token
        if (!response.data.token) {
          throw new Error('No authentication token received');
        }

        // Store token in localStorage
        localStorage.setItem('user_token', response.data.token);
        
        // Set token and user in store
        this.token = response.data.token;
        this.user = response.data.user;

        // Fetch additional user details
        await this.fetchUserProfile();

        return response.data;
      } catch (error) {
        console.error('Login Error:', error);
        // Clear any potentially invalid token
        localStorage.removeItem('user_token');
        this.token = null;
        throw error;
      }
    },

    async logout() {
      try {
        await axios.post('/v1/auth/logout');
        
        // Clear local storage and store state
        localStorage.removeItem('user_token');
        this.token = null;
        this.user = null;
        this.employer = null;
        this.jobSeeker = null;
      } catch (error) {
        console.error('Logout Error:', error);
        throw error;
      }
    },

    async fetchUserProfile() {
      try {
        const response = await axios.get('/v1/auth/me');
        this.user = response.data;

        // Determine user type and fetch corresponding profile
        if (response.data.role === 'employer') {
          await this.fetchEmployerProfile();
        } else if (response.data.role === 'job_seeker') {
          await this.fetchJobSeekerProfile();
        }
      } catch (error) {
        console.error('Fetch Profile Error:', error);
        throw error;
      }
    },

    async fetchEmployerProfile() {
      try {
        // Add explicit error handling for token
        if (!this.token) {
          throw new Error('No authentication token available');
        }

        const response = await axios.get('/v1/employers');
        
        if (response.data.length === 0) {
          console.warn('No employer profile found');
          this.employer = null;
          return null;
        }

        this.employer = response.data[0];
        return this.employer;
      } catch (error) {
        console.error('Fetch Employer Profile Error:', error);
        this.employer = null;
        
        // If unauthorized, force logout
        if (error.response && error.response.status === 401) {
          await this.logout();
        }
        
        throw error;
      }
    },

    async fetchJobSeekerProfile() {
      try {
        const response = await axios.get('/v1/job-seekers');
        this.jobSeeker = response.data[0] || null;
      } catch (error) {
        console.error('Fetch Job Seeker Profile Error:', error);
        this.jobSeeker = null;
      }
    },

    async getCurrentEmployer() {
      // Ensure token exists before fetching employer
      if (!this.token) {
        console.warn('No authentication token found. Please log in.');
        return null;
      }

      // If employer is not in store, try to fetch it
      if (!this.employer) {
        await this.fetchEmployerProfile();
      }
      return this.employer;
    },

    async register(userData) {
      try {
        const response = await axios.post('/v1/auth/register', userData);
        
        // Store token in localStorage
        localStorage.setItem('user_token', response.data.token);
        
        // Set token and user in store
        this.token = response.data.token;
        this.user = response.data.user;

        // Fetch additional user details
        await this.fetchUserProfile();

        return response.data;
      } catch (error) {
        console.error('Registration Error:', error);
        throw error;
      }
    }
  }
});
