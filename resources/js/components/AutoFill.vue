<template>
  <div class="document-form">
    <h2>Document Details</h2>
    <form @submit.prevent="saveDocument" class="form-container">
      <div class="form-group">
        <label>Document Type:</label>
        <select v-model="document.document_type">
          <option>Travel Order</option>
          <option>Office Order</option>
          <option>Special Order</option>
        </select>
      </div>
      <div class="form-group">
        <label>Travel Order Number:</label>
        <input type="text" v-model="document.document_no" />
      </div>
      <div class="form-group">
        <label>Series No:</label>
        <input type="text" v-model="document.series_no" />
      </div>
      <div class="form-group">
        <label>Date Issued:</label>
        <input type="date" v-model="document.date_issued" />
      </div>
      <div class="form-group">
        <label>From:</label>
        <input type="text" v-model="document.from_date" />
      </div>
      <div class="form-group">
        <label>To:</label>
        <input type="text" v-model="document.to_date" />
      </div>
      <div class="form-group">
        <label>Subject:</label>
        <input type="text" v-model="document.subject" />
      </div>
      <div class="form-group">
        <label>Description:</label>
        <textarea v-model="document.description"></textarea>
      </div>

      <!-- Employee Names Section -->
      <div class="form-group">
        <label>Employee Names:</label>
        <transition-group name="fade" tag="div">
          <div v-for="(employee, index) in document.employee_names" :key="index" class="employee-group">
            <input type="text" v-model="document.employee_names[index]" />
            <button type="button" class="btn-remove" @click="removeEmployee(index)">Remove</button>
          </div>
        </transition-group>
        <button type="button" class="btn-add" @click="addEmployee">Add Employee</button>
      </div>

      <button type="submit" class="btn-submit">Save</button>
    </form>

    <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
    <div v-if="successMessage" class="success">{{ successMessage }}</div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      document: {
        document_no: "",
        series_no: "",
        date_issued: "",
        from: "",
        to: "",
        subject: "",
        description: "",
        document_type: "Travel Order",
        employee_names: [], // Array to store employee names
        file_path: "", // For storing the file path returned from the backend
      },
      errorMessage: null,
      successMessage: null,
    };
  },
  created() {
    // Retrieve the extracted data from localStorage
    const extractedData = localStorage.getItem("extractedData");
    if (extractedData) {
      try {
        this.document = JSON.parse(extractedData);

        // Format the date if it's present to work with the input type="date"
        if (this.document.date_issued) {
          this.document.date_issued = this.formatDate(this.document.date_issued);
        }

        // Ensure employee names exist as an array
        if (!Array.isArray(this.document.employee_names)) {
          this.document.employee_names = [];
        }
      } catch (error) {
        console.error("Error parsing extracted data from localStorage:", error);
      }
    }
  },
  methods: {
    async saveDocument() {
      try {
        this.clearMessages(); // Clear previous messages

        // Add basic validation here if needed
        if (!this.document.document_type || !this.document.file_path) {
          this.errorMessage = "Please ensure all required fields are filled.";
          return;
        }

        const response = await axios.post("/api/admin/documents/save", this.document);

        if (response.data) {
          this.successMessage = "Document saved successfully.";
          localStorage.removeItem("extractedData"); // Clean up localStorage after saving
        }
      } catch (error) {
        this.errorMessage = error.response?.data?.error || "Failed to save the document.";
      }
    },
    formatDate(dateStr) {
      // Assuming date is received in a format like 'January 25, 2024'
      const dateObj = new Date(dateStr);
      return dateObj.toISOString().split("T")[0]; // Return in YYYY-MM-DD format
    },
    clearMessages() {
      this.errorMessage = null;
      this.successMessage = null;
    },
    addEmployee() {
      this.document.employee_names.push(""); // Add an empty string to the array
    },
    removeEmployee(index) {
      this.document.employee_names.splice(index, 1); // Remove employee at the specified index
    },
  },
};
</script>

<style scoped>
/* Form Styling */
.document-form {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  margin-left: 20px;
  margin-right: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

.form-container {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 5px;
  font-weight: bold;
}

input[type="text"], textarea, select, input[type="date"] {
  padding: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
  font-size: 1rem;
}

textarea {
  resize: none;
  height: 100px;
}

button {
  cursor: pointer;
  border: none;
  border-radius: 4px;
  padding: 10px;
  font-size: 1rem;
}

.btn-add {
  background-color: #4caf50;
  color: white;
  width: 500px;
}

.btn-remove {
  background-color: #f44336;
  color: white;
  margin-left: 10px;
}

.btn-submit {
  background-color: #007bff;
  color: white;
  margin-top: 20px;
}

.error {
  color: red;
  margin-top: 10px;
  text-align: center;
}

.success {
  color: green;
  margin-top: 10px;
  text-align: center;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}

.employee-group input[type="text"] {
  width: 400px; /* Adjust the width as needed */
}

.employee-group {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}
</style>
