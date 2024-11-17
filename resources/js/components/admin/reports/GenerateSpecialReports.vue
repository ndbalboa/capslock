<template>
  <h2>Reports | Special Orders</h2>
  <div class="container">
    <div class="row">
      <!-- Filter Section -->
      <div class="col-lg-12">
        <div class="card bg-light text-dark">
          <div class="card-body">
            <h5>Generate Special Order Report</h5>
            <form @submit.prevent="generateReport">
              <!-- Date Filter -->
              <div class="form-row align-items-center">
                <label class="col-md-3 col-form-label">Select Upload Date Range:</label>
                <div class="col-md-4 mb-3">
                  <input type="date" v-model="startDate" class="form-control" required />
                  <small>From</small>
                </div>
                <div class="col-md-4 mb-3">
                  <input type="date" v-model="endDate" class="form-control" required />
                  <small>To</small>
                </div>
              </div>

              <!-- Generate Button -->
              <div class="form-row">
                <div class="col-md-2 mb-3">
                  <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Loading Spinner -->
      <div v-if="isLoading" class="col-lg-12 text-center mt-3">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="col-lg-12 mt-3">
        <div class="alert alert-danger" role="alert">
          {{ errorMessage }}
        </div>
      </div>

      <!-- Document Table -->
      <div v-if="documents.length > 0" class="col-lg-12 mt-3">
        <h5>Travel Order Documents from {{ startDate }} to {{ endDate }}</h5>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Destination</th>
              <th>Subject</th>
              <th>Inclusive Date</th>
              <th>Beneficiaries</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="document in documents" :key="document.id">
              <td>{{ document.destination }}</td>
              <td>{{ document.subject }}</td>
              <td>{{ document.from_date }} &ndash; {{ document.to_date }}</td>
              <td>{{ document.employee_names }}</td>
              <td>
                <a :href="'/storage/' + document.file_path" class="btn btn-info" target="_blank">View</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      startDate: '',
      endDate: '',
      isLoading: false,
      errorMessage: '',
      reportGenerated: false,
      reportLink: '',
      documents: [], // Stores the list of documents
    };
  },
  methods: {
    // Generate report and fetch documents for the date range
    async generateReport() {
      // Validate if dates are selected
      if (!this.startDate || !this.endDate) {
        this.errorMessage = 'Please select both a start and end date.';
        return;
      }

      // Validate if start date is before end date
      if (this.startDate > this.endDate) {
        this.errorMessage = 'The start date cannot be later than the end date.';
        return;
      }

      this.isLoading = true;
      this.errorMessage = '';
      this.reportGenerated = false;
      this.documents = []; // Clear the documents list before fetching

      try {
        // First, generate the report
        const reportResponse = await axios.post('/api/admin/generate-report', {
          upload_from_date: this.startDate,
          upload_to_date: this.endDate,
          document_type: 'Special Order', // Specify document type
        });

        this.reportLink = reportResponse.data.file_path;
        this.reportGenerated = true;

        // Then, fetch the documents for the same date range
        const documentsResponse = await axios.get('/api/admin/documentsbydaterange', {
          params: {
            upload_from_date: this.startDate,
            upload_to_date: this.endDate,
            document_type: 'Special Order', // Filter documents by type
          },
        });

        this.documents = documentsResponse.data.documents; // Set the documents data
      } catch (error) {
        // Handle errors from the backend
        this.errorMessage = error.response?.data?.message || 'An error occurred while generating the report.';
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>
