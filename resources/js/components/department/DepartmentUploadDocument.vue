<template>
  <div class="upload-document">
    <h1>Upload Document</h1>
    <form @submit.prevent="submitDocument">
      <!-- Select Document Type -->
      <div class="form-group">
        <label for="documentType">Document Type</label>
        <select v-model="form.documentTypeId" id="documentType" class="form-control" @change="onDocumentTypeChange">
          <option value="" disabled>Select Document Type</option>
          <option v-for="type in documentTypes" :key="type.id" :value="type.id">
            {{ type.document_type }}
          </option>
        </select>
      </div>

      <!-- File Upload -->
      <div class="form-group">
        <label for="file">File</label>
        <input type="file" id="file" class="form-control" @change="onFileChange" />
      </div>

      <!-- Additional Fields (Visible only after document type selection) -->
      <div v-if="form.documentTypeId">
        <!-- Document Number -->
        <div class="form-group">
          <label for="documentNo">Document Number</label>
          <input
            type="text"
            id="documentNo"
            class="form-control"
            v-model="form.documentNo"
            placeholder="Enter document number"
          />
        </div>

        <!-- Date Issued -->
        <div class="form-group">
          <label for="dateIssued">Date Issued</label>
          <input type="date" id="dateIssued" class="form-control" v-model="form.dateIssued" />
        </div>

        <!-- From Date -->
        <div class="form-group">
          <label for="fromDate">From Date</label>
          <input type="date" id="fromDate" class="form-control" v-model="form.fromDate" />
        </div>

        <!-- To Date -->
        <div class="form-group">
          <label for="toDate">To Date</label>
          <input type="date" id="toDate" class="form-control" v-model="form.toDate" />
        </div>

        <!-- Venue (shown only for Travel Order) -->
        <div class="form-group" v-if="showVenueAndDestination">
          <label for="venue">Venue</label>
          <input type="text" id="venue" class="form-control" v-model="form.venue" placeholder="Enter venue" />
        </div>

        <!-- Destination (shown only for Travel Order) -->
        <div class="form-group" v-if="showVenueAndDestination">
          <label for="destination">Destination</label>
          <input
            type="text"
            id="destination"
            class="form-control"
            v-model="form.destination"
            placeholder="Enter destination"
          />
        </div>

        <!-- Subject -->
        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" class="form-control" v-model="form.subject" placeholder="Enter subject" />
        </div>

        <!-- Description -->
        <div class="form-group">
          <label for="description">Description</label>
          <textarea
            id="description"
            class="form-control"
            v-model="form.description"
            placeholder="Enter description"
          ></textarea>
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary">Upload</button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      documentTypes: [], // List of document types
      form: {
        documentTypeId: "", // Selected document type ID
        documentNo: "",
        dateIssued: "",
        fromDate: "",
        toDate: "",
        venue: "",
        destination: "",
        subject: "",
        description: "",
        file: null, // Uploaded file
      },
    };
  },
  computed: {
    // Show venue and destination only if the document type is Travel Order
    showVenueAndDestination() {
      const selectedType = this.documentTypes.find(
        (type) => type.id === this.form.documentTypeId
      );
      return selectedType?.document_type === "Travel Order";
    },
  },
  methods: {
    // Fetch document types from the server
    async fetchDocumentTypes() {
      try {
        const response = await axios.get("/api/admin/upload/document-types");
        this.documentTypes = response.data;
      } catch (error) {
        console.error("Error fetching document types:", error);
      }
    },
    // Handle file selection
    onFileChange(event) {
      this.form.file = event.target.files[0];
    },
    // Handle document type change
    onDocumentTypeChange() {
      // Reset venue and destination if not Travel Order
      if (!this.showVenueAndDestination) {
        this.form.venue = "";
        this.form.destination = "";
      }
    },
    // Submit the document form
    async submitDocument() {
      const formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      try {
        const response = await axios.post("/api/admin/upload/documents", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });
        alert("Document uploaded successfully!");
        console.log(response.data);
      } catch (error) {
        console.error("Error uploading document:", error);
        alert("Failed to upload document.");
      }
    },
  },
  mounted() {
    this.fetchDocumentTypes();
  },
};
</script>

<style>
.upload-document {
  max-width: 600px;
  margin: 0 auto;
}

.form-group {
  margin-bottom: 1rem;
}

.btn {
  display: block;
  width: 100%;
}
</style>
