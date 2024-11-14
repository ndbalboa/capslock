<template>
  <h2>Reports | Office Orders</h2>
  <div class="container">
    <div class="row">
      <!-- Filter Section -->
      <div class="col-lg-12">
        <div class="card bg-transparent text-white">
          <div class="card-body">
            <h5>Generate Office Order Report</h5>
            <form @submit.prevent="generateReport">
              
              <!-- Date Filter -->
              <div class="form-row align-items-center">
                <label class="col-md-3 col-form-label">Select Date Range:</label>
                <div class="col-md-4 mb-3">
                  <input type="date" v-model="startDate" class="form-control" required />
                  <small>From</small>
                </div>
                <div class="col-md-4 mb-3">
                  <input type="date" v-model="endDate" class="form-control" required />
                  <small>To</small>
                </div>
              </div>

              <!-- Employee Filter -->
              <div class="form-row align-items-center">
                <label class="col-md-3 col-form-label">Select Employee:</label>
                <div class="col-md-4 mb-3">
                  <select v-model="employeeName" class="form-control">
                    <option value="">---All---</option>
                    <option v-for="employee in employees" :key="employee.id" :value="employee.name">
                      {{ employee.name }}
                    </option>
                  </select>
                </div>
              </div>

              <!-- Generate Button -->
              <div class="form-row">
                <div class="col-md-2 mb-3">
                  <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Report Table Section -->
      <div class="col-lg-12 mt-3">
        <div v-if="documents.length > 0">
          <h5>Office Orders List</h5>
          <table class="table table-striped table-bordered">
            <thead class="thead-dark">
              <tr>
                <th>Subject</th>
                <th>Inclusive Date</th>
                <th>Venue</th>
                <th>Beneficiaries</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(document, index) in documents" :key="document.id">
                <td>{{ document.subject }}</td>
                <td>{{ document.from_date }} &ndash; {{ document.to_date }}</td>
                <td>{{ document.venue }}</td>
                <td>{{ document.employee_names }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else>
          <div class="alert alert-warning" role="alert">
            No travel orders found for the selected date range.
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
      startDate: '',
      endDate: '',
      employeeName: '',  
      documents: [],  
      employees: [] 
    };
  },
  methods: {
    async fetchEmployees() {
      try {
        const response = await axios.get('/api/admin/employees');
        this.employees = response.data.employees;
      } catch (error) {
        console.error('Error fetching employees:', error);
      }
    },
    async generateReport() {
      try {
        const response = await axios.post('/api/admin/generate-report', {
          start_date: this.startDate,
          end_date: this.endDate,
          document_type: 'Office Order',
          employee_name: this.employeeName || null
        });
        this.documents = response.data;
      } catch (error) {
        console.error('Error generating report:', error);
      }
    }
  },
  mounted() {
    this.fetchEmployees();
  }
};
</script>

<style scoped>
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.card {
  border-radius: 10px;
  background-color: transparent;
}

.card-body {
  padding: 25px;
}

.form-row {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

label {
  font-weight: bold;
  margin-right: 10px;
  color: rgb(21, 17, 17);
}
table th {
  text-align: center;
  vertical-align: middle;
  background-color: navy; 
  color: white;  
  height: 40px;  
  white-space: nowrap;
}
table td {
  text-align: center;
  vertical-align: middle;
}

thead {
  background-color: #343a40;
  color: rgb(21, 17, 17);
}

button {
  cursor: pointer;
}

button:hover {
  background-color: #007bff;
  color: white;
}

.alert-warning {
  margin-top: 20px;
}
</style>
