import axios from 'axios';

// Create an Axios instance with base configuration
const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

// Request interceptor to add authentication token
apiClient.interceptors.request.use(
  config => {
    const token = localStorage.getItem('user_token');


    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`;

    } else {
      console.error('NO TOKEN FOUND IN LOCAL STORAGE');
    }



    return config;
  },
  error => {
    console.error('Request Interceptor Error:', error);
    return Promise.reject(error);
  }
);

// Response interceptor for error handling
apiClient.interceptors.response.use(
  response => {

    return response;
  },
  error => {
    console.error('FULL ERROR DETAILS:', {
      status: error.response?.status,
      data: error.response?.data,
      headers: error.response?.headers,
      config: error.config
    });

    // Handle specific error scenarios
    if (error.response) {
      console.error('Detailed Response Error:', {
        status: error.response.status,
        data: error.response.data,
        headers: error.response.headers
      });

      switch (error.response.status) {
        case 401: // Unauthorized
          console.error('Unauthorized Access Details:', error.response.data);
          localStorage.removeItem('token');
          window.location = '/login';
          break;
        case 403: // Forbidden
          console.error('Access Denied Details:', error.response.data);
          break;
        case 404: // Not Found
          console.error('Resource not found');
          break;
        case 422: // Validation Error
          console.error('Validation Error Details:', error.response.data);
          break;
        case 500: // Server Error
          console.error('Internal server error');
          break;
      }
    } else if (error.request) {
      // The request was made but no response was received
      console.error('No response received:', error.request);
    } else {
      // Something happened in setting up the request that triggered an Error
      console.error('Error setting up request:', error.message);
    }
    return Promise.reject(error);
  }
);

export default apiClient;
