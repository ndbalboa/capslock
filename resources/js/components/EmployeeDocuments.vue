<template>
  <div class="container">
    <h2 class="mt-3">Employee Documents</h2>
    <table class="table table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>Document ID</th>
          <th>Document Type</th>
          <th>Document Name</th>
          <th>Date Issued</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="document in documents" :key="document.id">
          <td>{{ document.id }}</td>
          <td>{{ document.type }}</td>
          <td>{{ document.name }}</td>
          <td>{{ document.date_issued }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'DocumentsTravelOrder',
  props: ['id'], // Accept employee ID from route parameters
  data() {
    return {
      documents: [],
    };
  },
  created() {
    this.fetchEmployeeDocuments(this.id);
  },
  methods: {
    async fetchEmployeeDocuments(employeeId) {
      try {
        const response = await axios.get(`/api/employees/${employeeId}/documents`);
        this.documents = response.data.documents;
      } catch (error) {
        console.error('Error fetching documents:', error);
      }
    },
  },
};
</script>
