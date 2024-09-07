<template>
    <div class="container mt-4">
      <h2>Change Username and Password</h2>
      <form @submit.prevent="changeCredentials">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input
            type="text"
            id="username"
            v-model="form.username"
            class="form-control"
            required
          />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">New Password</label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="form-control"
            required
          />
        </div>
        <div class="mb-3">
          <label for="confirmPassword" class="form-label">Confirm New Password</label>
          <input
            type="password"
            id="confirmPassword"
            v-model="form.confirmPassword"
            class="form-control"
            required
          />
        </div>
        <button type="submit" class="btn btn-primary">Update Credentials</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        form: {
          username: '',
          password: '',
          confirmPassword: ''
        }
      };
    },
    methods: {
      async changeCredentials() {
        if (this.form.password !== this.form.confirmPassword) {
          alert('Passwords do not match');
          return;
        }
  
        try {
          const response = await axios.put('/api/user/change-credentials', {
            username: this.form.username,
            password: this.form.password,
            password_confirmation: this.form.confirmPassword
          });
          alert('Credentials updated successfully');
          // Handle success (e.g., redirect to another page or clear form)
        } catch (error) {
          console.error('Error updating credentials:', error);
          alert('Failed to update credentials');
        }
      }
    }
  };
  </script>
  
  <style scoped>
  /* Optional: Add custom styles if needed */
  </style>
  