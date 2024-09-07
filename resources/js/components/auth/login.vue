<template>
  <div class="login-container">
    <div class="login-box">
      <div class="text-center mb-4">
        <h2 class="mt-3">Leyte Normal University</h2>
        <h4>Optimized Categorization of Records Management System</h4>
      </div>
      <form @submit.prevent="login">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
          </div>
          <input type="text" class="form-control" placeholder="Username" v-model="username" required>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
          </div>
          <input type="password" class="form-control" placeholder="Password" v-model="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Log In</button>
        <div v-if="errorMessage" class="alert alert-danger mt-3">
          {{ errorMessage }}
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

axios.defaults.withCredentials = true; // Ensure credentials (like cookies) are sent with requests

export default {
  name: 'login',
  data() {
    return {
      username: '',
      password: '',
      errorMessage: ''
    };
  },
  methods: {
    async login() {
      try {
        await axios.get('/sanctum/csrf-cookie');
        const response = await axios.post('/api/login', {
          username: this.username,
          password: this.password
        });
        const token = response.data.access_token;
        const role = response.data.role;

        // Store the token and user info in localStorage
        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify({ role }));

        // Set the authorization header for future requests
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        if (role === 'admin') {
          this.$router.push('/admin-dashboard');
        } else {
          this.$router.push('/user-dashboard');
        }
      } catch (error) {
        if (error.response) {
          // Server responded with a status other than 2xx
          this.errorMessage = error.response.data.message || 'An error occurred. Please try again.';
        } else if (error.request) {
          // Request was made but no response received
          this.errorMessage = 'No response from server. Please check your connection.';
        } else {
          // Something happened in setting up the request
          this.errorMessage = 'Error: ' + error.message;
        }
        console.error(error);
      }
    }
  }
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-size: cover;
  background-position: center;
}
.login-box {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  width: 100%;
}
</style>
