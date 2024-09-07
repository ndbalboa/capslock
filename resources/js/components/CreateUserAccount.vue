<template>
  <div class="create-user">
    <h2>Create User Account</h2>
    <form @submit.prevent="createUser" class="create-user-form">
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="employee_id">Employee ID</label>
          <input id="employee_id" v-model="employee_id" placeholder="Employee ID" required />
        </div>
        <div class="col-md-4 mb-3">
          <label for="firstName">First Name</label>
          <input id="firstName" v-model="firstName" placeholder="First Name" required />
        </div>
        <div class="col-md-4 mb-3">
          <label for="lastName">Last Name</label>
          <input id="lastName" v-model="lastName" placeholder="Last Name" required />
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="email">Email</label>
          <input id="email" type="email" v-model="email" placeholder="Email" required />
        </div>
        <div class="col-md-4 mb-3">
          <label for="username">Username</label>
          <input id="username" v-model="username" placeholder="Username" required />
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="password">Password</label>
          <input id="password" type="password" v-model="password" placeholder="Password" required />
        </div>
        <div class="col-md-4 mb-3">
          <label for="confirmPassword">Confirm Password</label>
          <input id="confirmPassword" type="password" v-model="confirmPassword" placeholder="Confirm Password" required />
        </div>
        <div class="col-md-4 mb-3">
          <label for="role">Role</label>
          <select id="role" v-model="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select>
        </div>
      </div>

      <button type="submit" class="submit-button">Create Account</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      employee_id: '',
      firstName: '',
      lastName: '',
      email: '',
      username: '',
      password: '',
      confirmPassword: '',
      role: 'user',
    };
  },
  methods: {
    async createUser() {
      try {
        const response = await axios.post('/api/admin/users', {
          employee_id: this.employee_id,
          firstName: this.firstName,
          lastName: this.lastName,
          email: this.email,
          username: this.username,
          password: this.password,
          password_confirmation: this.confirmPassword,
          role: this.role,
        });

        console.log('User created successfully:', response.data);
      } catch (error) {
        if (error.response && error.response.status === 422) {
          console.error('Validation errors:', error.response.data.errors);
        } else {
          console.error('Error creating user:', error);
        }
      }
    },
  },
};
</script>

<style scoped>
.create-user {
  padding: 30px;
  background-color: #f3f4f6;
  border-radius: 10px;
  
  margin: 0 auto;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  color: #333;
  margin-bottom: 30px;
  font-size: 24px;
}

.create-user-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.row {
  display: flex;
  gap: 20px;
}

.col-md-4 {
  flex: 1;
}

label {
  font-weight: bold;
  margin-bottom: 5px;
  display: block;
  color: #555;
}

input,
select {
  width: 100%;
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 14px;
  transition: border-color 0.3s;
}

input:focus,
select:focus {
  border-color: #007bff;
  outline: none;
}

.submit-button {
  padding: 12px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  text-align: center;
  transition: background-color 0.3s;
}

.submit-button:hover {
  background-color: #0056b3;
}
</style>
