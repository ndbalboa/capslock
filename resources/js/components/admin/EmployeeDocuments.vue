<template>
  <div class="document-list-container">
    <!-- Employee Header -->
    <h2 v-if="employee.firstName && employee.lastName" class="employee-header">
      {{ employee.firstName }} {{ employee.lastName }}'s Documents
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
        <tr
          v-for="document in documents"
          :key="document.id"
          :class="{ 'selected-row': document.id === selectedDocumentId }"
          @click="selectDocument(document.id)"
        >
          <td>{{ document.document_no }}</td>
          <td>{{ document.subject || 'Subject not found' }}</td>
          <td>{{ document.description || 'Description not found' }}</td>
          <td>{{ document.date_issued }}</td>
          <td>{{ document.document_type }}</td>
        </tr>
      </tbody>
    </table>

    <!-- No Documents Message -->
    <p v-else class="no-documents">No documents found for this employee.</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['id'],
  data() {
    return {
      employee: {},
      documents: [],
      selectedDocumentId: null, // Tracks the selected document
    };
  },
  methods: {
    // Fetch employee details
    fetchEmployeeDetails() {
      axios
        .get(`/api/admin/employees/${this.id}`)
        .then((response) => {
          this.employee = response.data;
          this.fetchEmployeeDocuments();
        })
        .catch((error) => {
          console.error('Error fetching employee details:', error);
        });
    },
    // Fetch documents associated with the employee
    fetchEmployeeDocuments() {
      axios
        .get(`/api/admin/employees/${this.id}/documents`)
        .then((response) => {
          this.documents = response.data;
        })
        .catch((error) => {
          console.error('Error fetching employee documents:', error);
        });
    },
    // Redirect to document details
    selectDocument(documentId) {
      this.selectedDocumentId = documentId; // Set the active document ID
      this.$router.push({ name: 'DocumentDetails', params: { id: documentId } }); // Redirect to DocumentDetails
    },
  },
  mounted() {
    this.fetchEmployeeDetails(); // Fetch employee details on component mount
  },
};
</script>

<style scoped>
.document-list-container {
  max-width: 1200px;
  margin: auto;
  padding: 20px;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.employee-header {
  font-family: 'Arial', sans-serif;
  font-size: 1.5em;
  color: #333;
  margin-bottom: 20px;
  text-align: center;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table th,
.table td {
  padding: 12px;
  text-align: left;
  border: 1px solid #ddd;
}

.table th {
  background-color: #f2f2f2;
  font-weight: bold;
  color: #555;
}

.selected-row {
  background-color: #87ceeb; /* Sky blue */
}

.no-documents {
  text-align: center;
  color: #777;
  font-size: 1em;
  margin-top: 20px;
}
</style>
