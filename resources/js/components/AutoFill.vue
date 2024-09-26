<template>
  <div class="autofill-container">
    <h2>Autofill Document Data</h2>

    <div v-if="extractedData" class="form-group">
      <label>Document No:</label>
      <input v-model="extractedData.document_no" class="input-field" />

      <label>Date Issued:</label>
      <input v-model="extractedData.date_issued" class="input-field" />

      <label>From:</label>
      <input v-model="extractedData.from" class="input-field" />

      <label>To:</label>
      <input v-model="extractedData.to" class="input-field" />

      <label>Subject:</label>
      <input v-model="extractedData.subject" class="input-field" />

      <label>Description:</label>
      <textarea v-model="extractedData.description" class="textarea-field" rows="4"></textarea>

      <label>Document Type:</label>
      <input v-model="documentType" class="input-field" readonly />

      <button @click="saveDocument" class="save-button">Save Document</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';

export default {
  data() {
    return {
      extractedData: {},
      documentType: ''
    };
  },
  mounted() {
    const route = useRoute();
    this.extractedData = JSON.parse(route.query.extractedData || '{}'); // Parse the JSON string
    this.documentType = route.query.documentType || ''; // Get document type from query
  },
  methods: {
    saveDocument() {
      const payload = {
        document_no: this.extractedData.document_no,
        date_issued: this.extractedData.date_issued,
        from: this.extractedData.from,
        to: this.extractedData.to,
        subject: this.extractedData.subject,
        description: this.extractedData.description,
        document_type: this.documentType,
        employee_names: this.extractedData.employee_names,
      };

      axios.post('/api/admin/documents/save', payload)
        .then(response => {
          alert('Document saved successfully.');
        })
        .catch(error => {
          console.error('Error saving document:', error);
        });
    }
  }
};
</script>

<style scoped>
.autofill-container {
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.input-field,
.textarea-field {
  width: 100%;
  padding: 8px;
  border: 1px solid #f5eeee;
  border-radius: 4px;
  box-sizing: border-box;
}

.textarea-field {
  resize: vertical;
}

.save-button {
  background-color: #4c86af; 
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.save-button:hover {
  background-color: #45a049;
}
</style>
