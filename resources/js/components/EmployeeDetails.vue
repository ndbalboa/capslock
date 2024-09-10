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
          </div>
          <div class="row mt-3">
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
          </div>
          <div class="row mt-3">
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
      </div>
        <!-- Profile Image and Buttons -->
        <div class="profile-actions">
        <button @click="viewEmployeeDocuments" class="btn btn-primary">View Employee's Documents</button>
        <div class="profile-image-container">
          <img :src="employee.profileImage || 'default-profile.png'" class="profile-image" alt="Profile Image">
        </div>
        <div v-if="isEditing" class="mt-3">
          <input type="file" @change="handleImageUpload" accept="image/*" />
          <button v-if="employee.profileImage && !imageToDelete" @click="confirmDeleteImage" class="btn btn-danger mt-2">Delete Image</button>
        </div>

        <!-- Edit, Save, Cancel buttons -->
        <div class="d-flex justify-content-end mt-3">
          <button v-if="!isEditing" @click="enableEdit" class="btn btn-warning">Update Employee Information</button>
          <button v-if="isEditing" @click="saveEmployeeDetails" class="btn btn-success me-2">Save</button>
          <button v-if="isEditing" @click="cancelEdit" class="btn btn-secondary">Cancel</button>
          <!-- Delete Button -->
          <button @click="deleteEmployee" class="btn btn-danger ms-2">Delete Employee</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Loading state -->
  <div v-else>
    <p class="text-center">Loading...</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      employee: null,
      isEditing: false,
      selectedImage: null,
      imageToDelete: false,
    };
  },
  created() {
    const employeeId = this.$route.params.id;
    if (employeeId) {
      this.fetchEmployeeDetails(employeeId);
    } else {
      console.error('Employee ID is undefined');
    }
  },
  methods: {
    async fetchEmployeeDetails(employeeId) {
      try {
        const response = await axios.get(`/api/admin/employees/${employeeId}`);
        this.employee = response.data.employee;
      } catch (error) {
        console.error('Error fetching employee details:', error);
      }
    },
    enableEdit() {
      this.isEditing = true;
    },
    cancelEdit() {
      this.isEditing = false;
      this.fetchEmployeeDetails(this.employee.id); // Reset the form to original data
    },
    async saveEmployeeDetails() {
      try {
        if (this.selectedImage) {
          const formData = new FormData();
          formData.append('profileImage', this.selectedImage);
          await axios.post(`/api/admin/employees/${this.employee.id}/upload-image`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          });
        } else if (this.imageToDelete) {
          await axios.post(`/api/admin/employees/${this.employee.id}/delete-image`);
        }
        await axios.put(`/api/admin/employees/${this.employee.id}`, this.employee);
        this.isEditing = false;
        alert('Employee details updated successfully!');
      } catch (error) {
        console.error('Error saving employee details:', error);
        alert('Failed to update employee details');
      }
    },
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.selectedImage = file;
        this.imageToDelete = false;
      }
    },
    confirmDeleteImage() {
      if (confirm('Are you sure you want to delete this image?')) {
        this.imageToDelete = true;
        this.selectedImage = null;
      }
    },
    async deleteEmployee() {
      if (confirm('Are you sure you want to delete this employee?')) {
        try {
          await axios.delete(`/api/admin/employees/${this.employee.id}`);
          alert('Employee has been deleted.');
          this.$router.push({ name: 'EmployeeList' });
        } catch (error) {
          console.error('Error deleting employee:', error);
          alert('Failed to delete employee');
        }
      }
    },
    viewEmployeeDocuments() {
      this.$router.push({ name: 'EmployeeDocuments', params: { id: this.employee.id } });
    }
  }
};
</script>

<style scoped>
.profile-info-container {
  display: flex;
  justify-content: space-between;
}
.personal-info {
  flex: 1;
}
.profile-actions {
  flex-basis: 350px; 
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-left: 20px; 
}
.profile-image-container {
  height: 450px;
  width: 300px;
  position: relative;
  background-image: url('/user.png');
  background-size: cover;
  background-position: center;
  border-radius: 10px;
  overflow: hidden;
}
.profile-image {
  height: 100%;
  width: 100%;
  object-fit: cover;
}
.documents-button {
  margin-top: 0;
}
.section-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: #333;
}
</style>