<template>
  <div>
    <h2>Upload Document</h2>
    <input type="file" @change="handleFileUpload" />
    <button @click="uploadDocument">Upload</button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      selectedFile: null,
    };
  },
  methods: {
    handleFileUpload(event) {
      this.selectedFile = event.target.files[0];
    },
    uploadDocument() {
      const formData = new FormData();
      formData.append('file', this.selectedFile);

      axios.post('http://localhost:5000/api/admin/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
          'Accept': 'application/json',
        },
        withCredentials: true, // Include credentials if necessary
      })
      .then(response => {
        const extractedData = response.data.extracted_data;
        const documentType = response.data.document_type;

        // Redirect to Autofill.vue with the extracted data using query parameters
        this.$router.push({
          name: 'Autofill',
          query: {
            extractedData: JSON.stringify(extractedData), // Convert to JSON string
            documentType: documentType,
          }
        });
      })
      .catch(error => {
        console.error(error);
      });
    }
  }
};
</script>
