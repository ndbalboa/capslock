<template>
    <div class="container mt-5">
      <div class="card shadow p-4">
        <h3 class="mb-4">Upload New Document</h3>
        <form @submit.prevent="handleSubmit">
          <!-- Select Document Type -->
          <div class="form-group mb-3">
            <label for="documentType" class="form-label">Select Document Type</label>
            <select
              id="documentType"
              v-model="documentType"
              class="form-select"
              required
            >
              <option value="" disabled>Select Document Type</option>
              <option value="travel_order">Travel Order</option>
              <option value="office_order">Office Order</option>
              <option value="other">Other</option>
            </select>
          </div>
  
          <!-- File Upload -->
          <div class="form-group mb-3">
            <label for="file" class="form-label">Choose File</label>
            <input
              type="file"
              id="file"
              class="form-control"
              @change="handleFileUpload"
              required
            />
          </div>
  
          <!-- Upload Button -->
          <button type="submit" class="btn btn-primary w-100">
            Upload Document
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        documentType: '',
        file: null,
      };
    },
    methods: {
      handleFileUpload(event) {
        this.file = event.target.files[0];
      },
      handleSubmit() {
        if (this.file && this.documentType) {
          const formData = new FormData();
          formData.append('documentType', this.documentType);
          formData.append('file', this.file);
  
          // Perform API call to upload the file
          console.log('Form submitted', formData);
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .container {
    max-width: 600px;
    margin: auto;
  }
  .card {
    border-radius: 10px;
  }
  h3 {
    font-weight: bold;
  }
  .form-label {
    font-weight: 500;
  }
  </style>
  