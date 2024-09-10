<template>
    <div class="document-form">
      <h2>Fill Out Document</h2>
      <form>
        <label>Document Type</label>
        <select v-model="documentType" disabled>
          <option>{{ documentType }}</option>
        </select>
  
        <label>Serial Number</label>
        <input type="text" v-model="serialNumber" disabled />
  
        <label>Subject</label>
        <input type="text" v-model="subject" />
  
        <label>Description</label>
        <input type="text" v-model="description" />
  
        <label>Date Issued</label>
        <input type="date" v-model="dateIssued" />
  
        <button type="submit">Save As</button>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        documentType: '',
        serialNumber: '',
        subject: '',
        description: '',
        dateIssued: ''
      };
    },
    created() {
      const { scannedData } = this.$route.params;
      if (scannedData) {
        this.documentType = scannedData.documentType;
        this.serialNumber = scannedData.serialNumber;
        this.subject = scannedData.subject;
        this.description = scannedData.description;
        this.dateIssued = scannedData.dateIssued;
      }
    }
  };
  </script>
  
  <style>
  /* Add relevant styling based on the screenshot */
  .document-form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: #fff;
  }
  h2 {
    font-size: 24px;
    margin-bottom: 20px;
  }
  form {
    display: flex;
    flex-direction: column;
  }
  label {
    margin-bottom: 10px;
    font-weight: bold;
  }
  input, select {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
  }
  button {
    padding: 10px;
    background-color: #4b80fe;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  </style>
  