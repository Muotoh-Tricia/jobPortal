import axios from 'axios';

// Create an axios instance with default config
const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api/v1',
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
    const errorMessage = error.response.data.message || 
                         (error.response.data.errors && 
                          Object.values(error.response.data.errors).flat()[0]) || 
                         customMessage;
    throw new Error(errorMessage);
  } else if (error.request) {
    // The request was made but no response was received
    throw new Error('No response received from server');
  } else {
    // Something happened in setting up the request that triggered an Error
    throw new Error(error.message || customMessage);
  }
};

export default {
  // User Registration
  async register(userData) {
    try {
      const response = await apiClient.post('/auth/register', userData);
      return response.data;
    } catch (error) {
      handleError(error, 'Registration failed');
    }
  },

  // User Login
  async login(credentials) {
    try {
      const response = await apiClient.post('/auth/login', credentials);
      
      // Store token and user info
      if (response.data.token) {
        localStorage.setItem('user_token', response.data.token);
        localStorage.setItem('user_info', JSON.stringify(response.data.user));
      }
      
      return response.data;
    } catch (error) {
      handleError(error, 'Login failed');
    }
  },

  // User Logout
  async logout() {
    try {
      // Get the current token
      const token = localStorage.getItem('user_token');
      
      // Only attempt backend logout if token exists
      if (token) {
        try {
          await apiClient.post('/auth/logout', {}, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
        } catch (logoutError) {
          // If logout fails due to invalid/expired token, 
          // we'll still proceed with clearing local storage
          console.warn('Backend logout failed:', logoutError);
        }
      }
      
      // Always clear local storage
      localStorage.removeItem('user_token');
      localStorage.removeItem('user_info');
      
      return true;
    } catch (error) {
      // Unexpected error
      console.error('Unexpected logout error:', error);
      
      // Ensure local storage is cleared even if an error occurs
      localStorage.removeItem('user_token');
      localStorage.removeItem('user_info');
      
      throw error;
    }
  },

  // Enhanced token validation method
  isTokenValid() {
    const token = localStorage.getItem('user_token');
    const userInfo = localStorage.getItem('user_info');
    
    // Check if both token and user info exist
    if (!token || !userInfo) {
      return false;
    }
    
    try {
      // Optional: Add more sophisticated token validation if needed
      // For example, check token expiration
      const user = JSON.parse(userInfo);
      return !!user;
    } catch (error) {
      // If parsing fails, consider token invalid
      return false;
    }
  },

  // Override existing isAuthenticated method
  isAuthenticated() {
    return this.isTokenValid();
  },

  // Get current user
  getCurrentUser() {
    const userInfo = localStorage.getItem('user_info');
    return userInfo ? JSON.parse(userInfo) : null;
  }
};
