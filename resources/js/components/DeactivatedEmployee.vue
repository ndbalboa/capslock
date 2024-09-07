<template>
  <div class="container">
    <h2 class="mt-3">List of Deactivated Employees</h2>
    <div class="d-flex justify-content-end mb-3">
      <div class="input-group" style="width: 300px;">
        <input
          type="text"
          class="form-control"
          placeholder="Search"
          v-model="searchQuery"
        />
        <div class="input-group-append">
          <span class="input-group-text">
            <i class="fa fa-search"></i>
          </span>
        </div>
      </div>
    </div>
    <nav aria-label="Page navigation" class="d-flex justify-content-end">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <a class="page-link" @click.prevent="fetchEmployees(currentPage - 1)" href="#">Previous</a>
        </li>
        <li
          class="page-item"
          v-for="page in totalPages"
          :key="page"
          :class="{ active: currentPage === page }"
        >
          <a class="page-link" @click.prevent="fetchEmployees(page)" href="#">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
          <a class="page-link" @click.prevent="fetchEmployees(currentPage + 1)" href="#">Next</a>
        </li>
      </ul>
    </nav>
    <table class="table table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Email</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="employee in filteredEmployees"
          :key="employee.id"
          @click="viewEmployee(employee.id)"
          class="clickable-row"
        >
          <td>{{ employee.id }}</td>
          <td>{{ employee.lastName }}</td>
          <td>{{ employee.firstName }}</td>
          <td>{{ employee.emailAddress }}</td>
          <td>{{ (employee.status === 'active') ? 'Active' : 'Inactive' }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';

export default {
  setup() {
    const employees = ref([]);
    const searchQuery = ref('');
    const currentPage = ref(1);
    const totalPages = ref(1);
    const router = useRouter();

    const fetchEmployees = (page = 1) => {
      axios
        .get(`/api/admin/employees/deactivated?page=${page}`)
        .then(response => {
          console.log('Fetched deactivated employees:', response.data);
          employees.value = response.data.data; // Ensure this matches your API response structure
          currentPage.value = response.data.current_page;
          totalPages.value = response.data.last_page;
        })
        .catch(error => {
          console.error('Error fetching deactivated employees:', error);
        });
    };

    const filteredEmployees = computed(() => {
      const query = searchQuery.value.toLowerCase();
      return employees.value.filter(employee => {
        return (
          employee.id.toString().includes(query) ||
          employee.lastName.toLowerCase().includes(query) ||
          employee.firstName.toLowerCase().includes(query) ||
          employee.emailAddress.toLowerCase().includes(query)
        );
      });
    });

    const viewEmployee = id => {
      router.push(`/employees/${id}`);
    };

    fetchEmployees();

    return {
      employees,
      searchQuery,
      filteredEmployees,
      viewEmployee,
      currentPage,
      totalPages,
      fetchEmployees,
    };
  },
};
</script>

<style scoped>
.clickable-row {
  cursor: pointer;
}
.input-group {
  width: 300px;
}
</style>
