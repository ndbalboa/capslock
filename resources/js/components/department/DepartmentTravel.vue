<template>
  <div>
    <h2>Documents for HRMO Department</h2>
    
    <div v-if="loading" class="spinner">
      <p>Loading...</p>
    </div>
    
    <table v-if="documents.length" class="table">
      <thead>
        <tr>
          <th>Document Type</th>
          <th>Document Number</th>
          <th>Employee Names</th>
          <th>File Path</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(docs, documentType) in groupedDocuments" :key="documentType">
          <tr v-for="document in docs" :key="document.id">
            <td>{{ documentType }}</td>
            <td>{{ document.document_no }}</td>
            <td>
              <span v-for="(employee, index) in document.employee_names" :key="index">
                {{ employee }}<span v-if="index < document.employee_names.length - 1">, </span>
              </span>
            </td>
            <td>{{ document.file_path }}</td>
          </tr>
        </template>
      </tbody>
    </table>

    <p v-else>No documents found.</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      documents: [],
      groupedDocuments: {},
      loading: true
    };
  },
  created() {
    this.fetchDocuments();
  },
  methods: {
    async fetchDocuments() {
      try {
        // Replace this with your actual API endpoint
        const response = await this.$axios.get('/api/documents'); 

        if (response.data) {
          this.documents = response.data;
          this.groupDocumentsByType();
        }
      } catch (error) {
        console.error("Error fetching documents:", error);
      } finally {
        this.loading = false;
      }
    },
    groupDocumentsByType() {
      this.groupedDocuments = this.documents.reduce((group, document) => {
        const documentType = document.document_type; // Assuming document_type is in the response
        if (!group[documentType]) {
          group[documentType] = [];
        }
        group[documentType].push(document);
        return group;
      }, {});
    }
  }
};
</script>

<style scoped>
.table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.table th, .table td {
  padding: 10px;
  border: 1px solid #ccc;
  text-align: left;
}

.spinner {
  text-align: center;
  margin-top: 20px;
}
</style>
