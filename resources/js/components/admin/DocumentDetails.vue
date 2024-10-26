<template>
  <h2>{{ document ? document.document_type : 'Travel Order' }}</h2>
  <div v-if="document" class="action-buttons">
    <button @click="viewFile" v-if="document.file_path" class="btn-primary">View File</button>
    <button @click="deleteDocument" class="btn-danger">Delete Document</button>
  </div>
  <div class="document-details-container">
    <div v-if="document" class="document-details">
      <div class="details-grid">
        <label for="doc-no"><strong>Document No:</strong></label>
        <input id="doc-no" v-model="document.document_no" readonly />

        <label for="series-no"><strong>Series Year:</strong></label>
        <input id="series-no" v-model="document.series_no" readonly />

        <div class="inclusive-date">
          <label><strong>Inclusive Date:</strong></label>
          <div class="date-range">
            <input id="from-date" :value="formatDate(document.from_date)" readonly />
            <span>to</span>
            <input id="to-date" :value="formatDate(document.to_date)" readonly />
          </div>
        </div>

        <label for="subject"><strong>Subject:</strong></label>
        <textarea 
          id="subject" 
          v-model="document.subject" 
          @input="resizeTextarea($event)" 
          readonly
        ></textarea>

        <label for="description"><strong>Description:</strong></label>
        <textarea 
          id="description" 
          v-model="document.description" 
          @input="resizeTextarea($event)" 
          readonly
        ></textarea>

        <label for="date-issued"><strong>Date Issued:</strong></label>
        <input id="date-issued" v-model="document.date_issued" readonly />

        <label for="employee-names"><strong>Employee Names:</strong></label>
        <textarea 
          id="employee-names" 
          v-model="employeeNames" 
          @input="resizeTextarea($event)" 
          readonly
        ></textarea>
      </div>
    </div>

    <div v-else-if="error" class="error-message">
      <p>{{ error }}</p>
    </div>

    <div v-else>
      <p>Loading document details...</p>
    </div>

    <button @click="goBack" class="btn-secondary">Go Back</button>
  </div>
</template>


<script>
import axios from 'axios';

export default {
  data() {
    return {
      document: null,
      error: null,
      appUrl: import.meta.env.VITE_APP_URL, // Accessing the VITE_APP_URL
    };
  },
  computed: {
    employeeNames() {
      return this.document && this.document.employee_names 
        ? this.document.employee_names.join('\n') 
        : '';
    },
  },
  methods: {
    async fetchDocumentDetails() {
      const documentId = this.$route.params.id;

      if (!documentId) {
        this.error = 'Invalid document ID.';
        return;
      }

      try {
        const response = await axios.get(`/api/admin/documents/${documentId}`);
        this.document = response.data; // Assign response data to document
        this.$nextTick(() => {
          this.autoResizeTextareas(); // Resize textareas after data is loaded
        });
      } catch (error) {
        console.error('Error fetching document details:', error);

        if (error.response && error.response.status === 404) {
          this.error = 'Document not found.';
        } else {
          this.error = 'Failed to load document details. Please try again later.';
        }
      }
    },
    formatDate(date) {
      if (!date) return '';
      const d = new Date(date);
      const month = (d.getMonth() + 1).toString().padStart(1, '0');
      const day = d.getDate().toString().padStart(2, '0');
      const year = d.getFullYear();
      return `${month}-${day}-${year}`; // Format: M-DD-YYYY
    },
    viewFile() {
      const fileUrl = this.getFileUrl(this.document.file_path);
      window.open(fileUrl, '_blank');
    },
    async deleteDocument() {
      if (confirm('Are you sure you want to delete this document?')) {
        try {
          await axios.delete(`/api/admin/documents/${this.document.id}`);
          alert('Document deleted successfully.');
          this.$router.push('/documents'); // Redirect after deletion
        } catch (error) {
          console.error('Error deleting document:', error);
          alert('Failed to delete document. Please try again later.');
        }
      }
    },
    goBack() {
      this.$router.go(-1); // Navigate back to the previous page
    },
    getFileUrl(filePath) {
      return `${this.appUrl}/${filePath}`;
    },
    autoResizeTextareas() {
      const textareas = this.$el.querySelectorAll('textarea');
      textareas.forEach(textarea => {
        textarea.style.height = 'auto'; // Reset height to auto to calculate new height
        textarea.style.height = `${textarea.scrollHeight}px`; // Set to scrollHeight
      });
    },
    resizeTextarea(event) {
      const textarea = event.target; // Get the textarea that triggered the event
      textarea.style.height = 'auto'; // Reset height to auto to calculate new height
      textarea.style.height = `${textarea.scrollHeight}px`; // Set to scrollHeight
    },
  },
  mounted() {
    this.fetchDocumentDetails(); // Fetch document details when the component mounts
  },
  watch: {
    document: {
      handler() {
        this.$nextTick(() => {
          this.autoResizeTextareas(); // Resize textareas when document changes
        });
      },
      deep: true,
    },
  },
};
</script>


<style scoped>
.document-details-container {
  margin: 20px auto;
  padding: 20px;
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  max-width: 720px;
  position: relative;
}

.action-buttons {
  margin-top: 90px;
  position: absolute;
  top: 10px;
  right: 10px;
  display: flex;
  gap: 10px;
}

h2 {
  text-align: left;
  font-family: 'Arial', sans-serif;
  color: #333;
  margin-bottom: 20px;
}

.details-grid {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 10px;
}

.details-grid label {
  font-weight: bold;
}

.details-grid input,
.details-grid textarea {
  font-size: 14px;
  padding: 8px;
  border-radius: 5px;
  border: 1px solid #ccc;
  background-color: #f9f9f9;
  resize: true; 
}

.details-grid textarea {
  height: auto; 
  min-height: 40px; 
  overflow: hidden; 
}
#description {
  height: 200px; 
}

.btn-primary,
.btn-danger,
.btn-secondary {
  display: inline-block;
  padding: 10px 20px;
  font-size: 14px;
  color: white;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
}

.btn-primary {
  background-color: #007bff;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.btn-danger {
  background-color: #dc3545;
}

.btn-danger:hover {
  background-color: #c82333;
}

.btn-secondary {
  background-color: #6c757d;
}

.btn-secondary:hover {
  background-color: #5a6268;
}

.error-message {
  color: red;
  text-align: center;
  margin-top: 20px;
}

.inclusive-date {
  grid-column: span 2; /* Keeps the Inclusive Date span across two columns */
  display: flex;
  align-items: center; /* Aligns items vertically centered */
  gap: 120px; /* Adds space between elements */
}

.inclusive-date .date-range {
  display: flex; /* Stays in a row */
  align-items: center; /* Aligns inputs vertically centered */
  gap: 5px; /* Space between the from/to input and the label */
}

.inclusive-date input {
  width: 130px; /* Set width for inputs */
}
</style>