<template>
  <div>
    <h1>Deactivated Employees</h1>

    <!-- Search Bar -->
    <div class="search-container">
      <input
        v-model="searchQuery"
        @input="searchEmployees"
        placeholder="Search by Name or ID"
        class="search-bar"
      />
    </div>

    <!-- Pagination Controls -->
    <div class="pagination">
      <button
        @click="changePage(currentPage - 1)"
        :disabled="currentPage === 1"
        class="btn"
      >
        Previous
      </button>
      <span>Page {{ currentPage }} of {{ totalPages }}</span>
      <button
        @click="changePage(currentPage + 1)"
        :disabled="currentPage === totalPages"
        class="btn"
      >
        Next
      </button>
    </div>

    <!-- Table of Deactivated Employees -->
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
        <tr v-for="employee in paginatedEmployees" :key="employee.id">
          <td>{{ employee.id }}</td>
          <td>{{ employee.firstName }} {{ employee.lastName }}</td>
          <td>{{ employee.email }}</td> <!-- Ensure your field names match -->
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
      employees: [],
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 5,
    };
  },
  computed: {
    filteredEmployees() {
      return this.employees.filter((employee) => {
        const fullName = `${employee.firstName} ${employee.lastName}`.toLowerCase();
        return (
          fullName.includes(this.searchQuery.toLowerCase()) ||
          employee.id.toString().includes(this.searchQuery)
        );
      });
    },
    paginatedEmployees() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredEmployees.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredEmployees.length / this.itemsPerPage);
    }
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
        alert('Failed to fetch deactivated employees.');
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
    },
    searchEmployees() {
      this.currentPage = 1; // Reset to first page after searching
    },
    changePage(page) {
      if (page > 0 && page <= this.totalPages) {
        this.currentPage = page;
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

/* Align search bar to the right and set a specific width */
.search-container {
  text-align: right;
  margin-bottom: 10px;
}

.search-bar {
  width: 300px; /* Set a fixed width for the search bar */
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ddd;
}

.pagination {
  display: flex;
  justify-content: flex-end; /* Align to the right */
  margin-bottom: 10px;
}

.pagination span {
  margin: 0 10px;
}
</style>
