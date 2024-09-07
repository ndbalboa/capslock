<template>
    <div>Logging out...</div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'Logout',
    async created() {
      try {
        await axios.post('/api/logout');
      } catch (error) {
        console.error(error);
      } finally {
        // Clear the token and user info from localStorage
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        
        // Remove the authorization header
        delete axios.defaults.headers.common['Authorization'];
        
        // Redirect to the login page
        this.$router.push('/');
      }
    }
  };
  </script>
  