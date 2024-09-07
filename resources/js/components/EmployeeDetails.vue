<template>
  <div v-if="employee" class="card profile-card">
    <div class="card-body profile-card-body">
      <!-- Profile Image and View Employee's Documents Button aligned to the right-top -->
      <div class="d-flex justify-content-end align-items-start mb-4">
        <div class="text-center me-3">
          <img :src="employee.profileImage || 'default-profile.png'" class="img-thumbnail profile-image" alt="Profile Image">
        </div>
        <button @click="viewEmployeeDocuments" class="btn btn-primary documents-button">View Employee's Documents</button>
      </div>

      <h2 class="section-title">Personal Information</h2>
      <div class="row">
        <div class="col">
          <label for="lastName">Last Name</label>
          <input type="text" id="lastName" class="form-control" v-model="employee.lastName" disabled>
        </div>
        <div class="col">
          <label for="firstName">First Name</label>
          <input type="text" id="firstName" class="form-control" v-model="employee.firstName" disabled>
        </div>
        <div class="col">
          <label for="middleName">Middle Name</label>
          <input type="text" id="middleName" class="form-control" v-model="employee.middleName" disabled>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label for="sex">Sex</label>
          <input type="text" id="sex" class="form-control" v-model="employee.sex" disabled>
        </div>
        <div class="col">
          <label for="civilStatus">Civil Status</label>
          <input type="text" id="civilStatus" class="form-control" v-model="employee.civilStatus" disabled>
        </div>
        <div class="col">
          <label for="dateOfBirth">Date of Birth</label>
          <input type="date" id="dateOfBirth" class="form-control" v-model="employee.dateOfBirth" disabled>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label for="emailAddress">Email Address</label>
          <input type="email" id="emailAddress" class="form-control" v-model="employee.emailAddress" disabled>
        </div>
        <div class="col">
          <label for="phoneNumber">Phone Number</label>
          <input type="text" id="phoneNumber" class="form-control" v-model="employee.phoneNumber" disabled>
        </div>
      </div>

      <!-- Permanent Address -->
      <h2 class="section-title mt-4">Permanent Address</h2>
      <div class="row">
        <div class="col">
          <label for="permanent_street">Street</label>
          <input type="text" id="permanent_street" class="form-control" v-model="employee.permanent_street" disabled>
        </div>
        <div class="col">
          <label for="permanent_barangay">Barangay</label>
          <input type="text" id="permanent_barangay" class="form-control" v-model="employee.permanent_barangay" disabled>
        </div>
        <div class="col">
          <label for="permanent_city">City</label>
          <input type="text" id="permanent_city" class="form-control" v-model="employee.permanent_city" disabled>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label for="permanent_province">Province</label>
          <input type="text" id="permanent_province" class="form-control" v-model="employee.permanent_province" disabled>
        </div>
        <div class="col">
          <label for="permanent_country">Country</label>
          <input type="text" id="permanent_country" class="form-control" v-model="employee.permanent_country" disabled>
        </div>
        <div class="col">
          <label for="permanent_zipcode">Zip Code</label>
          <input type="text" id="permanent_zipcode" class="form-control" v-model="employee.permanent_zipcode" disabled>
        </div>
      </div>

      <!-- Residential Address -->
      <h2 class="section-title mt-4">Residential Address</h2>
      <div class="row">
        <div class="col">
          <label for="residential_street">Street</label>
          <input type="text" id="residential_street" class="form-control" v-model="employee.residential_street" disabled>
        </div>
        <div class="col">
          <label for="residential_barangay">Barangay</label>
          <input type="text" id="residential_barangay" class="form-control" v-model="employee.residential_barangay" disabled>
        </div>
        <div class="col">
          <label for="residential_city">City</label>
          <input type="text" id="residential_city" class="form-control" v-model="employee.residential_city" disabled>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <label for="residential_province">Province</label>
          <input type="text" id="residential_province" class="form-control" v-model="employee.residential_province" disabled>
        </div>
        <div class="col">
          <label for="residential_country">Country</label>
          <input type="text" id="residential_country" class="form-control" v-model="employee.residential_country" disabled>
        </div>
        <div class="col">
          <label for="residential_zipcode">Zip Code</label>
          <input type="text" id="residential_zipcode" class="form-control" v-model="employee.residential_zipcode" disabled>
        </div>
      </div>
    </div>
  </div>
  <div v-else>
    <p class="text-center">Loading...</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      employee: null,
      error: null,
    };
  },
  created() {
    const employeeId = this.$route.params.id;
    this.fetchEmployeeDetails(employeeId);
  },
  methods: {
    async fetchEmployeeDetails(employeeId) {
      try {
        const response = await axios.get(`/api/admin/employees/${employeeId}`);
        this.employee = response.data.employee;
      } catch (error) {
        this.error = 'Failed to load employee details';
        console.error('Error fetching employee details:', error);
      }
    },
    viewEmployeeDocuments() {
      // Redirect to the employee's documents page using router push
      this.$router.push({ name: 'EmployeeDocuments', params: { id: this.employee.id } });
    },
  },
};
</script>


<style scoped>
.profile-card {
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin: 20px;
}

.profile-card-body {
  padding: 20px;
}

.section-title {
  font-size: 1.5rem;
  margin-bottom: 15px;
  font-weight: bold;
}

.profile-image {
  width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 50%;
}

.documents-button {
  height: 50px;
  align-self: flex-start;
}
</style>
