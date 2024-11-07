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
        <div class="text-center"> <!-- Center the button -->
          <button type="submit" class="btn btn-primary btn-block mt-3">Log In</button>
        </div>
        <div v-if="errorMessage" class="alert alert-danger mt-3">
          {{ errorMessage }}
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

axios.defaults.withCredentials = true;

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

        localStorage.setItem('token', token);
        localStorage.setItem('user', JSON.stringify({ role }));

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        if (role === 'admin') {
          this.$router.push('/admin-dashboard');
        } else if (role === 'secretary') {
          this.$router.push('/secretary-dashboard');
        } else {
          this.$router.push('/user-dashboard');
        }
      } catch (error) {
        if (error.response) {
          this.errorMessage = error.response.data.message || 'An error occurred. Please try again.';
        } else if (error.request) {
          this.errorMessage = 'No response from server. Please check your connection.';
        } else {
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
  background-image: url('/public/lnubacklogo.jpg');
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
  opacity: 90%;
}

.btn {
  width: 100%; /* Ensures the button takes up the full width of the login box */
}
</style>
