<template>
  <div class="report-container">
    <h2>Reports</h2>
    <p>Results 1-{{ reports.length }} out of {{ totalReports }} | Page {{ currentPage }} of {{ totalPages }}</p>
    
    <table class="report-table">
      <thead>
        <tr>
          <th>Description</th>
          <th>Date Generated</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="report in reports" :key="report.filePath">
          <td>{{ report.fileName }}</td>
          <td>{{ formatDate(report.created_at) }}</td>
          <td class="actions">
            <button class="btn btn-view" @click="viewReport(report.filePath)">View</button>
            <button class="btn btn-download" @click="downloadReport(report.filePath, report.fileName)">Download</button>
            <button class="btn btn-delete" @click="deleteReport(report.filePath)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination Controls -->
    <div class="pagination-controls">
      <button @click="fetchReports(currentPage - 1)" :disabled="currentPage <= 1">Previous</button>
      <button @click="fetchReports(currentPage + 1)" :disabled="currentPage >= totalPages">Next</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      reports: [],
      currentPage: 1,
      totalReports: 0,
      totalPages: 1,
      baseUrl: import.meta.env.VITE_BASE_URL || 'http://127.0.0.1:8000'  // Adjust for your env
    };
  },
  methods: {
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: true };
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', options);
    },
    viewReport(filePath) {
      const url = `${this.baseUrl}/storage/${filePath}`;
      window.open(url, '_blank');
    },
    downloadReport(filePath, fileName) {
      const url = `${this.baseUrl}/storage/${filePath}`;
      const link = document.createElement('a');
      link.href = url;
      link.download = fileName;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
    async deleteReport(filePath) {
      try {
        await axios.delete(`/api/admin/deletereport`, { data: { filePath } });
        this.reports = this.reports.filter(report => report.filePath !== filePath);
      } catch (error) {
        console.error('Error deleting report:', error);
      }
    },
    async fetchReports(page = 1) {
      try {
        const response = await axios.get(`/api/admin/listreport?page=${page}`);
        this.reports = response.data.reports;
        this.totalReports = response.data.totalReports;
        this.currentPage = response.data.currentPage;
        this.totalPages = response.data.totalPages;
      } catch (error) {
        console.error('Error fetching reports:', error);
      }
    }
  },
  mounted() {
    this.fetchReports();
  }
};
</script>

<style scoped>
.report-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
}

.report-container h2 {
  font-size: 24px;
  font-weight: bold;
}

.report-container p {
  font-size: 14px;
  color: #555;
  margin-bottom: 10px;
}

.report-table {
  width: 100%;
  border-collapse: collapse;
}

.report-table th, .report-table td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.report-table th {
  background-color: #003366;
  color: white;
}

.actions {
  display: flex;
  gap: 5px;
}

.btn {
  padding: 5px 10px;
  font-size: 12px;
  border: none;
  cursor: pointer;
}

.btn-view {
  background-color: #4CAF50;
  color: white;
}

.btn-download {
  background-color: #008CBA;
  color: white;
}

.btn-delete {
  background-color: #f44336;
  color: white;
}

.pagination-controls {
  margin-top: 20px;
  display: flex;
  justify-content: space-between;
}
</style>
