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
        localStorage.removeItem('token');
        localStorage.removeItem('user');
  
        delete axios.defaults.headers.common['Authorization'];

        this.$router.push('/');
      }
    }
  };
  </script>
  