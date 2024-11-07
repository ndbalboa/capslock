<template>
  <div v-if="employee" class="card profile-card">
    <div class="card-body profile-card-body">
      
      <!-- Profile Info and Actions -->
      <div class="profile-info-container">
        <!-- Personal Information -->
        <div class="personal-info">
          <h2 class="section-title">Personal Information</h2>
          <div class="row">
            <div class="col">
              <label for="lastName">Last Name</label>
              <input type="text" id="lastName" class="form-control" v-model="employee.lastName" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="firstName">First Name</label>
              <input type="text" id="firstName" class="form-control" v-model="employee.firstName" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="middleName">Middle Name</label>
              <input type="text" id="middleName" class="form-control" v-model="employee.middleName" :disabled="!isEditing">
            </div>
          </div>
          <div class="row mt-3">
            <div class="col">
              <label for="sex">Sex</label>
              <input type="text" id="sex" class="form-control" v-model="employee.sex" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="civilStatus">Civil Status</label>
              <input type="text" id="civilStatus" class="form-control" v-model="employee.civilStatus" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="dateOfBirth">Date of Birth</label>
              <input type="date" id="dateOfBirth" class="form-control" v-model="employee.dateOfBirth" :disabled="!isEditing">
            </div>
          </div>
          <div class="row mt-3">
            <div class="col">
              <label for="emailAddress">Email Address</label>
              <input type="email" id="emailAddress" class="form-control" v-model="employee.emailAddress" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="phoneNumber">Phone Number</label>
              <input type="text" id="phoneNumber" class="form-control" v-model="employee.phoneNumber" :disabled="!isEditing">
            </div>
          </div>

          <!-- Additional Personal Information -->
          <div class="row mt-3">
            <div class="col">
              <label for="gsisId">GSIS ID</label>
              <input type="text" id="gsisId" class="form-control" v-model="employee.gsisId" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="pagibigId">Pag-IBIG ID</label>
              <input type="text" id="pagibigId" class="form-control" v-model="employee.pagibigId" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="philhealthId">PhilHealth ID</label>
              <input type="text" id="philhealthId" class="form-control" v-model="employee.philhealthId" :disabled="!isEditing">
            </div>
          </div>
          <div class="row mt-3">
            <div class="col">
              <label for="sssNo">SSS Number</label>
              <input type="text" id="sssNo" class="form-control" v-model="employee.sssNo" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="agencyEmploymentNo">Agency Employment No.</label>
              <input type="text" id="agencyEmploymentNo" class="form-control" v-model="employee.agencyEmploymentNo" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="taxId">Tax ID</label>
              <input type="text" id="taxId" class="form-control" v-model="employee.taxId" :disabled="!isEditing">
            </div>
          </div>
          <div class="row mt-3">
            <div class="col">
              <label for="academicRank">Academic Rank</label>
              <input type="text" id="academicRank" class="form-control" v-model="employee.academicRank" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="universityPosition">University Position</label>
              <input type="text" id="universityPosition" class="form-control" v-model="employee.universityPosition" :disabled="!isEditing">
            </div>
          </div>

          <!-- Permanent Address -->
          <h2 class="section-title mt-4">Permanent Address</h2>
          <div class="row">
            <div class="col">
              <label for="permanent_street">Street</label>
              <input type="text" id="permanent_street" class="form-control" v-model="employee.permanent_street" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="permanent_barangay">Barangay</label>
              <input type="text" id="permanent_barangay" class="form-control" v-model="employee.permanent_barangay" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="permanent_city">City</label>
              <input type="text" id="permanent_city" class="form-control" v-model="employee.permanent_city" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="permanent_province">Province</label>
              <input type="text" id="permanent_province" class="form-control" v-model="employee.permanent_province" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="permanent_country">Country</label>
              <input type="text" id="permanent_country" class="form-control" v-model="employee.permanent_country" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="permanent_zipcode">Zip Code</label>
              <input type="text" id="permanent_zipcode" class="form-control" v-model="employee.permanent_zipcode" :disabled="!isEditing">
            </div>
          </div>

          <!-- Residential Address -->
          <h2 class="section-title mt-4">Residential Address</h2>
          <div class="row">
            <div class="col">
              <label for="residential_street">Street</label>
              <input type="text" id="residential_street" class="form-control" v-model="employee.residential_street" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="residential_barangay">Barangay</label>
              <input type="text" id="residential_barangay" class="form-control" v-model="employee.residential_barangay" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="residential_city">City</label>
              <input type="text" id="residential_city" class="form-control" v-model="employee.residential_city" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="residential_province">Province</label>
              <input type="text" id="residential_province" class="form-control" v-model="employee.residential_province" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="residential_country">Country</label>
              <input type="text" id="residential_country" class="form-control" v-model="employee.residential_country" :disabled="!isEditing">
            </div>
            <div class="col">
              <label for="residential_zipcode">Zip Code</label>
              <input type="text" id="residential_zipcode" class="form-control" v-model="employee.residential_zipcode" :disabled="!isEditing">
            </div>
          </div>
        </div>

        <!-- Profile Image -->
        <div class="profile-image mt-4">
          <img :src="profileImageUrl" alt="Profile Image" class="img-thumbnail">
          <input type="file" @change="onFileChange" :disabled="!isEditing">
        </div>

        <!-- Buttons -->
        <div class="mt-4">
          <div class="mt-4">
            <button v-if="!isEditing" @click="editProfile" class="btn btn-primary">
              <i class="fas fa-pencil-alt"></i> Edit
            </button>
            <button v-if="isEditing" @click="saveProfile" class="btn btn-success">
              <i class="fas fa-save"></i> Save
            </button>
            <button v-if="isEditing" @click="cancelEdit" class="btn btn-secondary">
              <i class="fas fa-times"></i> Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      employee: {},
      isEditing: false,
      profileImage: null
    };
  },
  methods: {
    fetchEmployeeProfile() {
      axios.get('/api/employee-profile')
        .then(response => {
          this.employee = response.data;
        })
        .catch(error => {
          console.error('Error fetching employee profile:', error);
        });
    },
    editProfile() {
      this.isEditing = true;
    },
    cancelEdit() {
      this.isEditing = false;
      this.fetchEmployeeProfile(); // Reset to fetched data
    },
    saveProfile() {
      const formData = new FormData();
      for (const key in this.employee) {
        if (this.employee.hasOwnProperty(key)) {
          formData.append(key, this.employee[key]);
        }
      }
      if (this.profileImage) {
        formData.append('profileImage', this.profileImage);
      }

      axios.post('/api/employee-profile', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(() => {
        this.isEditing = false;
        this.fetchEmployeeProfile(); // Refresh profile data
      })
      .catch(error => {
        if (error.response && error.response.status === 422) {
          // Handle validation errors
          console.error('Validation errors:', error.response.data.errors);
        } else {
          console.error('Error saving profile:', error);
        }
      });
    },
    onFileChange(event) {
      this.profileImage = event.target.files[0];
    }
  },
  mounted() {
    this.fetchEmployeeProfile();
  }
};
</script>

<style scoped>
/* Add any custom styling here */
.profile-card {
  max-width: 900px;
  margin: auto;
  padding: 20px;
}
.profile-card-body {
  padding: 20px;
}
.section-title {
  margin-top: 20px;
}
.profile-image img {
  max-width: 150px;
  border-radius: 50%;
}
</style>
