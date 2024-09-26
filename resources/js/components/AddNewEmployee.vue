<template>
  <div class="container mt-5">
    <h2>Add New Employee</h2>
    <h4 class="text-muted">Personal Information</h4>
    <form @submit.prevent="submitForm">
      <!-- Personal Information -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="lastName">Last Name</label>
          <input type="text" class="form-control" id="lastName" v-model="form.lastName" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="firstName">First Name</label>
          <input type="text" class="form-control" id="firstName" v-model="form.firstName" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="middleName">Middle Name</label>
          <input type="text" class="form-control" id="middleName" v-model="form.middleName">
        </div>
      </div>
      <div class="row">
        <div class="col-md-3 mb-3">
          <label for="sex">Sex</label>
          <select class="form-control" id="sex" v-model="form.sex" required>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </div>
        <div class="col-md-3 mb-3">
          <label for="civilStatus">Civil Status</label>
          <select class="form-control" id="civilStatus" v-model="form.civilStatus" required>
            <option>Single</option>
            <option>Married</option>
            <option>Divorced</option>
            <option>Widowed</option>
          </select>
        </div>
        <div class="col-md-3 mb-3">
          <label for="dateOfBirth">Date of Birth</label>
          <input type="date" class="form-control" id="dateOfBirth" v-model="form.dateOfBirth" required>
        </div>
        <div class="col-md-3 mb-3">
          <label for="religion">Religion</label>
          <input type="text" class="form-control" id="religion" v-model="form.religion">
        </div>
      </div>
      <!-- Contact Information -->
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="emailAddress">Email Address</label>
          <input type="email" class="form-control" id="emailAddress" v-model="form.emailAddress" required>
        </div>
        <div class="col-md-6 mb-3">
          <label for="phoneNumber">Phone Number</label>
          <input type="tel" class="form-control" id="phoneNumber" v-model="form.phoneNumber" required>
        </div>
      </div>
      <!-- Identification Numbers -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="gsisId">GSIS Id No.</label>
          <input type="text" class="form-control" id="gsisId" v-model="form.gsisId">
        </div>
        <div class="col-md-4 mb-3">
          <label for="pagibigId">PAG-IBIG Id No.</label>
          <input type="text" class="form-control" id="pagibigId" v-model="form.pagibigId">
        </div>
        <div class="col-md-4 mb-3">
          <label for="philhealthId">PhilHealth Id No.</label>
          <input type="text" class="form-control" id="philhealthId" v-model="form.philhealthId">
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="sssNo">SSS No.</label>
          <input type="text" class="form-control" id="sssNo" v-model="form.sssNo">
        </div>
        <div class="col-md-4 mb-3">
          <label for="agencyEmploymentNo">Agency Employment No.</label>
          <input type="text" class="form-control" id="agencyEmploymentNo" v-model="form.agencyEmploymentNo">
        </div>
        <div class="col-md-4 mb-3">
          <label for="taxId">Tax Identification No.</label>
          <input type="text" class="form-control" id="taxId" v-model="form.taxId">
        </div>
      </div>
      <!-- Academic and University Information -->
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="academicRank">Academic Rank</label>
          <select class="form-control" id="academicRank" v-model="form.academicRank">
            <option>Professor</option>
            <option>Associate Professor</option>
            <option>Assistant Professor</option>
            <option>Lecturer</option>
            <option>Instructor</option>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label for="universityPosition">University Position</label>
          <select class="form-control" id="universityPosition" v-model="form.universityPosition">
            <option>Dean</option>
            <option>Director</option>
            <option>Coordinator</option>
            <option>Faculty</option>
            <option>Staff</option>
          </select>
        </div>
      </div>
      <!-- Address and Profile Image -->
      <div class="row">
        <div class="col-md-6 mb-3">
          <h5>Permanent Address</h5>
        </div>
          <div class="row">
        <div class="col-md-4 mb-3">
          <label for="lastName">Street</label>
          <input type="text" class="form-control" id="lastName" v-model="form.permanent_street" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="firstName">Barangay</label>
          <input type="text" class="form-control" id="firstName" v-model="form.permanent_barangay" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="middleName">Town/City</label>
          <input type="text" class="form-control" id="middleName" v-model="form.permanent_city">
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="lastName">Province</label>
          <input type="text" class="form-control" id="lastName" v-model="form.permanent_province" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="firstName">Country</label>
          <input type="text" class="form-control" id="firstName" v-model="form.permanent_country" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="middleName">Zipcode</label>
          <input type="text" class="form-control" id="middleName" v-model="form.permanent_zipcode">
        </div>
      </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <h5>Residential Address</h5>
        </div>
          <div class="row">
        <div class="col-md-4 mb-3">
          <label for="lastName">Street</label>
          <input type="text" class="form-control" id="lastName" v-model="form.residential_street" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="firstName">Barangay</label>
          <input type="text" class="form-control" id="firstName" v-model="form.residential_barangay" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="middleName">Town/City</label>
          <input type="text" class="form-control" id="middleName" v-model="form.residential_city">
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
          <label for="lastName">Province</label>
          <input type="text" class="form-control" id="lastName" v-model="form.residential_province" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="firstName">Country</label>
          <input type="text" class="form-control" id="firstName" v-model="form.residential_country" required>
        </div>
        <div class="col-md-4 mb-3">
          <label for="middleName">Zipcode</label>
          <input type="text" class="form-control" id="middleName" v-model="form.residential_zipcode">
        </div>
      </div>

      </div>
      <button class="btn btn-primary" type="submit">Submit</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        lastName: '',
        firstName: '',
        middleName: '',
        sex: 'Male',
        civilStatus: 'Single',
        dateOfBirth: '',
        religion: '',
        emailAddress: '',
        phoneNumber: '',
        gsisId: '',
        pagibigId: '',
        philhealthId: '',
        sssNo: '',
        agencyEmploymentNo: '',
        taxId: '',
        academicRank: 'Professor',
        universityPosition: 'Dean',
        permanent_street: '',
        permanent_barangay: '',
        permanent_city: '',
        permanent_province: '',
        permanent_country: '',
        permanent_zipcode: '',
        residential_street: '',
        residential_barangay: '',
        residential_city: '',
        residential_province: '',
        residential_country: '',
        residential_zipcode: '',
      }
    };
  },
  methods: {
    submitForm() {
      const formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }

      axios.post('/api/admin/employees', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        alert('Employee added successfully!');
        this.$router.push('/api/admin/employees');
      })
      .catch(error => {
        console.error('There was an error adding the employee:', error);
      });
    }
  }
};
</script>

<style scoped>
.container {

  padding: 2px;
  margin-top: 0px;
}
</style>