<template>
  <div class="container">
    <h1 class="mt-3">Account Settings</h1>
    <!-- Check if userProfile is available before rendering the card -->
    <div v-if="userProfile" class="card profile-card">
      <div class="card-body profile-card-body">
        <h2>Personal Information</h2>
        <form @submit.prevent="updateUserProfile">
          <div class="row">
            <div class="col">
              <div class="row">
                <div class="col">
                  <label for="lastName">Last Name</label>
                  <input type="text" id="lastName" class="form-control" v-model="userProfile.employee.lastName" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="firstName">First Name</label>
                  <input type="text" id="firstName" class="form-control" v-model="userProfile.employee.firstName" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="middleName">Middle Name</label>
                  <input type="text" id="middleName" class="form-control" v-model="userProfile.employee.middleName" :disabled="!isEditing">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="sex">Sex</label>
                  <input type="text" id="sex" class="form-control" v-model="userProfile.employee.sex" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="civilStatus">Civil Status</label>
                  <input type="text" id="civilStatus" class="form-control" v-model="userProfile.employee.civilStatus" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="dateOfBirth">Date of Birth</label>
                  <input type="date" id="dateOfBirth" class="form-control" v-model="userProfile.employee.dateOfBirth" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="religion">Religion</label>
                  <input type="text" id="religion" class="form-control" v-model="userProfile.employee.religion" :disabled="!isEditing">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="emailAddress">Email Address</label>
                  <input type="email" id="emailAddress" class="form-control" v-model="userProfile.employee.emailAddress" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="phoneNumber">Phone Number</label>
                  <input type="text" id="phoneNumber" class="form-control" v-model="userProfile.employee.phoneNumber" :disabled="!isEditing">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="gsisId">GSIS ID</label>
                  <input type="text" id="gsisId" class="form-control" v-model="userProfile.employee.gsisId" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="pagibigId">PAGIBIG ID</label>
                  <input type="text" id="pagibigId" class="form-control" v-model="userProfile.employee.pagibigId" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="philhealthId">Philhealth ID</label>
                  <input type="text" id="philhealthId" class="form-control" v-model="userProfile.employee.philhealthId" :disabled="!isEditing">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="sssNo">SSS No</label>
                  <input type="text" id="sssNo" class="form-control" v-model="userProfile.employee.sssNo" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="agencyEmploymentNo">Agency Employment No</label>
                  <input type="text" id="agencyEmploymentNo" class="form-control" v-model="userProfile.employee.agencyEmploymentNo" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="taxId">Tax ID</label>
                  <input type="text" id="taxId" class="form-control" v-model="userProfile.employee.taxId" :disabled="!isEditing">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="academicRank">Academic Rank</label>
                  <input type="text" id="academicRank" class="form-control" v-model="userProfile.employee.academicRank" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="universityPosition">University Position</label>
                  <input type="text" id="universityPosition" class="form-control" v-model="userProfile.employee.universityPosition" :disabled="!isEditing">
                </div>
              </div>

              <!-- Permanent Address -->
              <h2 class="section-title mt-4">Permanent Address</h2>
              <div class="row">
                <div class="col">
                  <label for="permanent_street">Street</label>
                  <input type="text" id="permanent_street" class="form-control" v-model="userProfile.employee.permanent_street" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="permanent_barangay">Barangay</label>
                  <input type="text" id="permanent_barangay" class="form-control" v-model="userProfile.employee.permanent_barangay" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="permanent_city">City</label>
                  <input type="text" id="permanent_city" class="form-control" v-model="userProfile.employee.permanent_city" :disabled="!isEditing">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="permanent_province">Province</label>
                  <input type="text" id="permanent_province" class="form-control" v-model="userProfile.employee.permanent_province" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="permanent_country">Country</label>
                  <input type="text" id="permanent_country" class="form-control" v-model="userProfile.employee.permanent_country" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="permanent_zipcode">Zip Code</label>
                  <input type="text" id="permanent_zipcode" class="form-control" v-model="userProfile.employee.permanent_zipcode" :disabled="!isEditing">
                </div>
              </div>

              <!-- Residential Address -->
              <h2 class="section-title mt-4">Residential Address</h2>
              <div class="row">
                <div class="col">
                  <label for="residential_street">Street</label>
                  <input type="text" id="residential_street" class="form-control" v-model="userProfile.employee.residential_street" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="residential_barangay">Barangay</label>
                  <input type="text" id="residential_barangay" class="form-control" v-model="userProfile.employee.residential_barangay" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="residential_city">City</label>
                  <input type="text" id="residential_city" class="form-control" v-model="userProfile.employee.residential_city" :disabled="!isEditing">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="residential_province">Province</label>
                  <input type="text" id="residential_province" class="form-control" v-model="userProfile.employee.residential_province" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="residential_country">Country</label>
                  <input type="text" id="residential_country" class="form-control" v-model="userProfile.employee.residential_country" :disabled="!isEditing">
                </div>
                <div class="col">
                  <label for="residential_zipcode">Zip Code</label>
                  <input type="text" id="residential_zipcode" class="form-control" v-model="userProfile.employee.residential_zipcode" :disabled="!isEditing">
                </div>
              </div>
            </div>
            <div class="col">
              <div class="profile-image mt-4">
                <img :src="userProfile.employee.profileImage" alt="Profile Image" class="img-thumbnail" />
                <input type="file" @change="onImageSelected" class="form-control mt-2" />
                <button @click="uploadImage" class="btn btn-success mt-2">Upload Image</button>
              </div>
              <div class="mt-4">
                <button @click="toggleEdit" class="btn btn-primary">{{ isEditing ? 'Cancel' : 'Edit' }}</button>
                <button v-if="isEditing" type="submit" class="btn btn-success">Save</button>
              </div>
              <div class="documents-button">
                <button @click="viewDocuments" class="btn btn-info">View Documents</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div v-else>
      <p>Loading profile...</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      userProfile: null,
      isEditing: false,
      selectedImage: null,
    };
  },
  created() {
    this.fetchUserProfile();
  },
  methods: {
    fetchUserProfile() {
      axios.get('/api/user/profile')
        .then(response => {
          this.userProfile = response.data;
        })
        .catch(error => {
          console.error("Error fetching profile:", error);
        });
    },
    onImageSelected(event) {
      this.selectedImage = event.target.files[0];
    },
    uploadImage() {
      if (!this.selectedImage) return;

      const formData = new FormData();
      formData.append('profileImage', this.selectedImage);
      
      axios.post('/api/user/upload-image', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        this.userProfile.employee.profileImage = response.data.url;
        this.selectedImage = null; // Clear selected image after upload
        alert('Profile image updated successfully');
      })
      .catch(error => {
        console.error("Error uploading image:", error);
      });
    },
    updateUserProfile() {
      console.log('Updating profile with:', this.userProfile);
      axios.put('/api/user/profile', this.userProfile)
        .then(response => {
          console.log('Profile updated response:', response);
          this.userProfile = response.data;
          this.isEditing = false;
          alert('Profile updated successfully');
        })
        .catch(error => {
          console.error("Error updating profile:", error);
        });
    },
    toggleEdit() {
      this.isEditing = !this.isEditing;
    },
    viewDocuments() {
      // Logic to view documents
      alert('View Documents functionality not implemented');
    }
  }
};
</script>

<style scoped>
.container {
  max-width: 1200px;
}
.profile-card {
  border: 1px solid #ddd;
  border-radius: 5px;
}
.profile-card-body {
  padding: 20px;
}
.section-title {
  font-size: 1.5rem;
}
.profile-image img {
  max-width: 100%;
  height: auto;
}
.documents-button {
  margin-top: 20px;
}
</style>

