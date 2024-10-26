<template>
  <div class="document-container">
    <h2>Special Order</h2>

    <!-- Search Bar -->
    <div class="search-bar-container">
      <div class="search-bar-wrapper">
        <i class="fa fa-search search-icon"></i>
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search by document no, subject, employee name..."
          class="search-bar"
        />
      </div>
    </div>

    <!-- Pagination Controls (Below Search Bar) -->
    <div class="pagination-controls">
      <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
      <span>Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
    </div>

    <!-- Loading Indicator -->
    <div v-if="isLoading" class="loading-indicator">
      Loading documents...
    </div>

    <!-- Error Message -->
    <div v-if="error" class="error-message">
      {{ error }}
    </div>

    <!-- Table -->
    <table v-if="!isLoading && !error && paginatedDocuments.length" class="document-table">
      <thead>
        <tr>
          <th>Document No</th>
          <th>From Date</th>
          <th>To Date</th>
          <th>Subject</th>
          <th>Description</th>
          <th>Date Issued</th>
          <th>Employee Names</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="document in paginatedDocuments"
          :key="document.id"
          @click="goToDocumentDetails(document.id)"
          class="clickable-row"
        >
          <td>{{ document.document_no }}</td>
          <td>{{ document.from_date }}</td>
          <td>{{ document.to_date }}</td>
          <td>{{ document.subject }}</td>
          <td>{{ document.description }}</td>
          <td>{{ document.date_issued }}</td>
          <td>{{ document.employee_names.join(', ') }}</td>
        </tr>
      </tbody>
    </table>

    <!-- No Results Message -->
    <div v-if="!isLoading && !paginatedDocuments.length" class="no-results">
      No documents found.
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      documents: [],
      searchQuery: '',
      currentPage: 1,
      perPage: 5, // Items per page
      isLoading: false,
      error: null,
    };
  },
  computed: {
    filteredDocuments() {
      const query = this.searchQuery.toLowerCase();
      return this.documents.filter(
        (document) =>
          document.document_no?.toLowerCase().includes(query) ||
          document.subject?.toLowerCase().includes(query) ||
          document.employee_names?.some((name) =>
            name.toLowerCase().includes(query)
          )
      );
    },
    paginatedDocuments() {
      const start = (this.currentPage - 1) * this.perPage;
      const end = start + this.perPage;
      return this.filteredDocuments.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredDocuments.length / this.perPage);
    },
  },
  methods: {
    async fetchDocuments() {
      this.isLoading = true;
      this.error = null;
      try {
        const response = await axios.get('/api/user/documents/special order');
        this.documents = response.data;
      } catch (error) {
        console.error('Error fetching documents:', error);
        this.error = 'Failed to load documents. Please try again later.';
      } finally {
        this.isLoading = false;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    goToDocumentDetails(documentId) {
      this.$router.push({ name: 'UserDocumentDetails', params: { id: documentId } });
    },
  },
  mounted() {
    this.fetchDocuments();
  },
};
</script>

<style scoped>
.document-container {
  margin: 5px;
  padding: 10px;
  background-color: #f9f9f9;
  border-radius: 5px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
  color: #333;
  margin-bottom: 20px;
  font-family: 'Arial', sans-serif;
  text-align: center;
}

.search-bar-container {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 10px;
}

.search-bar-wrapper {
  position: relative;
  width: 100%;
  max-width: 300px;
}

.search-icon {
  position: absolute;
  top: 50%;
  left: 10px;
  transform: translateY(-50%);
  color: #888;
}

.search-bar {
  padding: 8px 12px 8px 30px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
  width: 100%;
  transition: border-color 0.3s;
}

.search-bar:focus {
  outline: none;
  border-color: #007bff;
}

.pagination-controls {
  display: flex;
  justify-content: flex-end;
  margin: 10px 0 20px;
}

.pagination-controls button {
  padding: 5px 10px;
  margin: 0 5px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.pagination-controls button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.pagination-controls button:hover:not(:disabled) {
  background-color: #0056b3;
}

.pagination-controls span {
  margin: 0 10px;
  font-size: 12px;
  text-align: center;
}

.document-table {
  width: 100%;
  border-collapse: collapse;
}

.document-table th,
.document-table td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.document-table th {
  background-color: #f1f1f1;
  font-weight: bold;
}

.document-table tr:hover {
  background-color: #f5f5f5;
  transition: background-color 0.3s ease;
}

.loading-indicator {
  text-align: center;
  margin: 20px;
  color: #007bff;
}

.error-message {
  color: red;
  text-align: center;
  margin: 20px;
}

.no-results {
  text-align: center;
  margin: 20px;
  color: #555;
}
</style>
