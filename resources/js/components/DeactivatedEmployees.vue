<template>
    <div>
      <h1>Deactivated Employees</h1>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="employee in employees" :key="employee.id">
            <td>{{ employee.id }}</td>
            <td>{{ employee.firstName }} {{ employee.lastName }}</td>
            <td>{{ employee.emailAddress }}</td>
            <td>{{ employee.status }}</td>
            <td>
              <button @click="restoreEmployee(employee.id)" class="btn btn-success">Restore</button>
              <button @click="forceDeleteEmployee(employee.id)" class="btn btn-danger">Permanently Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        employees: []
      };
    },
    created() {
      this.fetchDeactivatedEmployees();
    },
    methods: {
      async fetchDeactivatedEmployees() {
        try {
          const response = await axios.get('/api/admin/employees/deactivated');
          this.employees = response.data;
        } catch (error) {
          console.error('Error fetching deactivated employees:', error);
        }
      },
      async restoreEmployee(id) {
        try {
          await axios.post(`/api/admin/employees/${id}/restore`);
          this.fetchDeactivatedEmployees();
          alert('Employee restored successfully.');
        } catch (error) {
          console.error('Error restoring employee:', error);
          alert('Failed to restore employee.');
        }
      },
      async forceDeleteEmployee(id) {
        if (confirm('Are you sure you want to permanently delete this employee?')) {
          try {
            await axios.delete(`/api/admin/employees/${id}/forceDelete`);
            this.fetchDeactivatedEmployees();
            alert('Employee permanently deleted.');
          } catch (error) {
            console.error('Error permanently deleting employee:', error);
            alert('Failed to permanently delete employee.');
          }
        }
      }
    }
  };
  </script>
  
  <style scoped>
  .table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .table th, .table td {
    padding: 10px;
    border: 1px solid #ddd;
  }
  
  .table th {
    background-color: #f2f2f2;
  }
  
  .btn {
    padding: 5px 10px;
    margin: 2px;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
  }
  
  .btn-success {
    background-color: #28a745;
  }
  
  .btn-danger {
    background-color: #dc3545;
  }
  </style>
  