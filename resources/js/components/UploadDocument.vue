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
    <option value="memo">Memorandums</option>
    <option value="dbm">DBM Documents</option>
    <option value="records_office">Records Office Documents</option>
    <option value="others">Others</option>
  </select>
</div>

<!-- Memorandum Subtype -->
<div class="form-group mb-3" v-if="documentType === 'memo'">
  <label for="memoSubtype" class="form-label">Memorandum Document Type</label>
  <select
    id="memoSubtype"
    v-model="memoSubtye"
    class="form-select"
    required
  >
    <option disabled="memoSubtype">Select Memorandum Document Type</option>
    <option value="memo_circular">Memorandum Circular</option>
  </select>
</div>

<!-- DBM Document Subtype -->
<div class="form-group mb-3" v-if="documentType === 'dbm'">
  <label for="dbmSubtype" class="form-label">DBM Document Type</label>
  <select
    id="dbmSubtype"
    v-model="dbmSubtye"
    class="form-select"
    required
  >
    <option disabled="dbmSubtype">Select DBM Document Type</option>
    <option value="advice_of_nca_issued">Advice of NCA issued</option>
    <option value="advisory">Advisory</option>
    <option value="budget_circular">Budget Circular</option>
    <option value="circular_letter">Circular Letter</option>
    <option value="joint_circular">Joint Circular</option>
    <option value="nca">NCA</option>
    <option value="saro">SARO</option>
  </select>
</div>

<!-- Records Office Subtype -->
<div class="form-group mb-3" v-if="documentType === 'records_office'">
  <label for="records_officeSubtype" class="form-label">Records Office Document Type</label>
  <select
    id="records_officeSubtype"
    v-model="records_officeSubtype"
    class="form-select"
    required
  >
    <option disabled="records_officeSubtype">Select Records Office Document Type</option>
    <option value="certifies_true_copy">Certified True Copy</option>
    <option value="request">Request</option>
  </select>
</div>

<!-- Others Subtype -->
<div class="form-group mb-3" v-if="documentType === 'others'">
  <label for="othersSubtype" class="form-label">Others Document Type</label>
  <select
    id="othersSubtype"
    v-model="othersSubtype"
    class="form-select"
    required
  >
    <option disabled="othersSubtype">Document Type</option>
    <option value="addendum">ADDENDUM</option>
    <option value="authority_travel">Authority to Travel Abroad</option>
    <option value="bac_meetings">BAC (Meetings and others)</option>
    <option value="billing_statement">BILLING STATEMENT</option>
    <option value="certificate">CERTIFICATE</option>
    <option value="ched">CHED</option>
    <option value="coa_circular">COA Circular</option>
    <option value="communication_letter">Communication Letter</option>
    <option value="communication_letter_2">Communication/Letter</option>
    <option value="contract">Contract</option>
    <option value="contract_of_service">Contract of Service</option>
    <option value="disallowance">DISALLOWANCE</option>
    <option value="goods_bac">GOODS (BAC)</option>
    <option value="gad_documents">GAD DOCUMENTS</option>
    <option value="infra">INFRA</option>
    <option value="legal_docuement">Legal Documents</option>
    <option value="lnu-land">LNU-Land</option>
    <option value="memorandum">Memorandum of Understanding</option>
    <option value="moa">MOA</option>
    <option value="notice_meeting">Notice of Meeting</option>
    <option value="notice_public">Notice to the Public</option>
    <option value="office_memo">Office Memorandum</option>
    <option value="office_order">Office Order</option>
    <option value="pasuc">PASUC</option>
    <option value="performance_bonus">PERFORMANCE-BASED BONUS</option>
    <option value="saln">SALN</option>
    <option value="scholarship">Scholarship Contract</option>
    <option value="special_order">Special Order</option>
    <option value="travel_order">Travel Order</option>
    <option value="201_files">201 Files</option>
    <option value="mou">MOU</option>
    <option value="qms_documents">QMS Documents</option>
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
  