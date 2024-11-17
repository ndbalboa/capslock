<template>
  <div class="generated-reports">
    <h2>Generated Reports</h2>

    <table v-if="reports.length > 0">
      <thead>
        <tr>
          <th>Description</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="report in reports" :key="report.id">
          <td>{{ report.description }}</td>
          <td>{{ formatDate(report.created_at) }}</td>
          <td>
            <a :href="getFileUrl(report.filePath)" target="_blank" class="btn btn-primary">Download</a>
            <button @click="viewReport(report)" class="btn btn-info">View</button>
            <button @click="deleteReport(report.id)" class="btn btn-danger">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-else>No reports found.</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      reports: [], // Array to store reports
      appUrl: 'http://127.0.0.1:8000', // Base URL for accessing files
    };
  },
  mounted() {
    this.fetchReports(); // Fetch reports when the component is mounted
  },
  methods: {
    // Method to fetch reports from the API
    async fetchReports() {
      try {
        const response = await this.$axios.get('/api/admin/reports'); // API endpoint to fetch reports
        this.reports = response.data; // Store the fetched reports
      } catch (error) {
        console.error('Error fetching reports:', error);
      }
    },

    // Method to format the date
    formatDate(date) {
      return new Date(date).toLocaleString(); // Customize the date format as needed
    },

    // Method to handle viewing a report
    viewReport(report) {
      const fileUrl = this.getFileUrl(report.filePath);
      window.open(fileUrl, '_blank');  // Open the report in a new tab
    },

    // Method to handle deleting a report
    async deleteReport(reportId) {
      if (confirm('Are you sure you want to delete this report?')) {
        try {
          await this.$axios.delete(`/api/admin/reports/${reportId}`);  // API endpoint to delete the report
          this.reports = this.reports.filter(report => report.id !== reportId);  // Remove from UI
        } catch (error) {
          console.error('Error deleting report:', error);
        }
      }
    },

    // Method to construct the full URL for the file
    getFileUrl(filePath) {
      return `${this.appUrl}/storage/${filePath}`; // Construct full URL using appUrl and filePath
    },
  },
};
</script>

<style scoped>
.generated-reports table {
  width: 100%;
  border-collapse: collapse;
}

.generated-reports th, .generated-reports td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.generated-reports th {
  background-color: #f4f4f4;
}

.generated-reports .btn {
  padding: 5px 10px;
  color: #fff;
  background-color: #007bff;
  text-decoration: none;
  border-radius: 4px;
}

.generated-reports .btn:hover {
  background-color: #0056b3;
}

.generated-reports .btn-info {
  background-color: #17a2b8;
}

.generated-reports .btn-danger {
  background-color: #dc3545;
}
</style>
