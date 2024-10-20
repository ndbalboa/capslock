<template>
  <div>
\
    <h2 v-if="employee.firstName && employee.lastName">
      {{ employee.firstName }} {{ employee.lastName }}'s documents
    </h2>

    <!-- Table displaying documents -->
    <table v-if="documents.length > 0" class="table table-bordered">
      <thead>
        <tr>
          <th>Document No</th>
          <th>Subject</th>
          <th>Description</th>
          <th>Date Issued</th>
          <th>Document Type</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="document in documents" :key="document.id">
          <td>{{ document.document_no }}</td>
          <td>{{ document.subject || 'Subject not found' }}</td>
          <td>{{ document.description || 'Description not found' }}</td>
          <td>{{ document.date_issued }}</td>
          <td>{{ document.document_type}}</td>
        </tr>
      </tbody>
    </table>

    <p v-else>No documents found for this employee.</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['id'],
  data() {
    return {
      employee: {},
      documents: []
    };
  },
  methods: {
    fetchEmployeeDetails() {
      axios.get(`/api/admin/employees/${this.id}`)
        .then(response => {
          this.employee = response.data;
          this.fetchEmployeeDocuments();
        })
        .catch(error => {
          console.error('Error fetching employee details:', error);
        });
    },
    fetchEmployeeDocuments() {
      axios.get(`/api/admin/employees/${this.id}/documents`)
        .then(response => {
          this.documents = response.data;
        })
        .catch(error => {
          console.error('Error fetching employee documents:', error);
        });
    }
  },
  mounted() {
    this.fetchEmployeeDetails();
  }
};
</script>

<style scoped>
.table {
  width: 100%;
  border-collapse: collapse;
}

.table th, .table td {
  padding: 8px;
  text-align: left;
  border: 1px solid #ddd;
}

.table th {
  background-color: #f2f2f2;
}
</style>
