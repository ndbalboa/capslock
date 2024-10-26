<template>
  <div>
    <h2>Upload a Document</h2>
    <form @submit.prevent="uploadFile">
      <div>
        <input type="file" @change="handleFileUpload" accept=".pdf,.docx,.png,.jpg,.jpeg" />
      </div>
      <button type="submit" :disabled="isLoading">Upload</button>

      <!-- Loading bar -->
      <div v-if="isLoading" class="loading-bar">Uploading...</div>
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
      selectedFile: null,
      errorMessage: null,
      successMessage: null,
      isLoading: false, // To track the loading state
    };
  },
  methods: {
    handleFileUpload(event) {
      this.selectedFile = event.target.files[0];
    },
    async uploadFile() {
      this.clearMessages(); // Clear previous messages
      if (!this.selectedFile) {
        this.errorMessage = "Please select a file.";
        return;
      }

      const formData = new FormData();
      formData.append("file", this.selectedFile);

      try {
        this.isLoading = true; // Start loading

        const response = await axios.post("/api/admin/documents/upload", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });

        if (response.data && response.data.document) {
          this.successMessage = "File uploaded successfully.";
          localStorage.setItem("extractedData", JSON.stringify(response.data.document));

          // Redirect to Autofill.vue after upload
          this.$router.push({ name: "Autofill" });
        }
      } catch (error) {
        console.error("Error uploading file:", error.response || error.message);
        this.errorMessage = error.response?.data?.error || "Failed to upload the file.";
      } finally {
        this.isLoading = false; // Stop loading
      }
    },
    clearMessages() {
      this.errorMessage = null;
      this.successMessage = null;
    },
  },
};
</script>

<style scoped>
.error {
  color: red;
}
.success {
  color: green;
}
.loading-bar {
  margin-top: 10px;
  color: blue;
  font-weight: bold;
}
button:disabled {
  background-color: #ddd;
  cursor: not-allowed;
}
</style>
