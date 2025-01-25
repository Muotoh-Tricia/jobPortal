import axios from 'axios';

// Create an Axios instance with base configuration
const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
  timeout: 10000,
  headers: {
    'Accept': 'application/json'
  }
});

// Request interceptor to add authentication token
apiClient.interceptors.request.use(
  config => {
    const token = localStorage.getItem('user_token');

    console.group('API Request Interceptor');
    console.log('Request URL:', config.url);
    console.log('Base URL:', config.baseURL);
    console.log('Full URL:', config.baseURL + config.url);
    console.log('Request Method:', config.method);
    console.log('Token Present:', !!token);
    console.log('Token Length:', token ? token.length : 'N/A');
    console.log('Request Headers:', config.headers);

    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`;
      console.log('Token Added to Headers');
    } else {
      console.warn('NO TOKEN FOUND IN LOCAL STORAGE');
      // Optionally redirect to login if critical routes require authentication
      if (config.url.includes('/applications') || config.url.includes('/jobs/')) {
        console.error('Authentication required for this route');
        window.location = '/login';
      }
    }

    // Dynamically set Content-Type based on request data
    if (config.data instanceof FormData) {
      config.headers['Content-Type'] = 'multipart/form-data';
      console.log('Content-Type: multipart/form-data');
    } else {
      config.headers['Content-Type'] = 'application/json';
      console.log('Content-Type: application/json');
    }

    console.groupEnd();

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
    console.group('API Response');
    console.log('Response URL:', response.config.url);
    console.log('Response Status:', response.status);
    console.log('Response Data:', response.data);
    console.groupEnd();
    return response;
  },
  error => {
    console.error('API Error:', {
      status: error.response?.status,
      data: error.response?.data,
      headers: error.response?.headers
    });

    // Detailed error logging
    if (error.response) {
      console.error('Detailed Error Response:', {
        status: error.response.status,
        data: error.response.data,
        headers: error.response.headers,
        url: error.config.url,
        method: error.config.method
      });

      // Specific error handling
      switch (error.response.status) {
        case 400:
          console.error('Bad Request Details:', error.response.data);
          break;
        case 401:
          console.error('Unauthorized Details:', error.response.data);
          localStorage.removeItem('user_token');
          window.location = '/login';
          break;
        case 404:
          console.error('Not Found Details:', {
            url: error.config.url,
            baseURL: error.config.baseURL
          });
          break;
        case 422:
          console.error('Validation Error:', error.response.data.errors);
          break;
        case 500:
          console.error('Server Error:', error.response.data);
          break;
      }
    }

    return Promise.reject(error);
  }
);

export default apiClient;
