<template>
    <div class="container">
      <h2>Generate Document Report</h2>
      
      <form @submit.prevent="fetchReport">
        <div class="form-group">
          <label for="documentType">Document Type</label>
          <select v-model="documentType" id="documentType" class="form-control">
            <option value="travel order">Travel Order</option>
            <option value="office order">Office Order</option>
            <option value="special order">Special Order</option>
          </select>
        </div>
  
        <div class="form-group">
          <label for="startDate">Start Date</label>
          <input type="date" v-model="startDate" id="startDate" class="form-control" required>
        </div>
  
        <div class="form-group">
          <label for="endDate">End Date</label>
          <input type="date" v-model="endDate" id="endDate" class="form-control" required>
        </div>
  
        <button type="submit" class="btn btn-primary mt-3">Generate Report</button>
      </form>
  
      <div v-if="documents.length" class="mt-4">
        <h3>Document Report</h3>
        <table class="table">
          <thead>
            <tr>
              <th>Document No</th>
              <th>Series No</th>
              <th>Date Issued</th>
              <th>Subject</th>
              <th>Description</th>
              <th>Uploaded At</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="document in documents" :key="document.id">
              <td>{{ document.document_no }}</td>
              <td>{{ document.series_no }}</td>
              <td>{{ document.date_issued }}</td>
              <td>{{ document.subject }}</td>
              <td>{{ document.description }}</td>
              <td>{{ document.created_at }}</td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <p v-else-if="hasSearched">No documents found for the specified criteria.</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        documentType: 'travel order',
        startDate: '',
        endDate: '',
        documents: [],
        hasSearched: false,
      };
    },
    methods: {
      async fetchReport() {
        try {
          const response = await axios.get('', {
            params: {
              document_type: this.documentType,
              start_date: this.startDate,
              end_date: this.endDate,
            },
          });
          this.documents = response.data;
          this.hasSearched = true;
        } catch (error) {
          console.error('Error fetching report:', error);
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 800px;
    margin: 0 auto;
  }
  </style>
  