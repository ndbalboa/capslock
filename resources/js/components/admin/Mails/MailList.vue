<template>
    <div><h1>Mail List</h1></div>
    <div class="mails-list">
      <div class="filters">
        <label for="dateFrom">Date</label>
        <input type="date" v-model="filters.dateFrom" />
  
        <span>to</span>
        
        <input type="date" v-model="filters.dateTo" />
  
        <label>Priority</label>
        <div class="priority-filters">
          <label v-for="priority in priorities" :key="priority">
            <input
              type="checkbox"
              :value="priority"
              v-model="filters.priority"
            /> {{ priority }}
          </label>
        </div>
  
        <label>Status</label>
        <div class="status-filters">
          <label>
            <input
              type="checkbox"
              value="Undelivered"
              v-model="filters.status"
            /> Undelivered
          </label>
          <label>
            <input
              type="checkbox"
              value="Delivered"
              v-model="filters.status"
            /> Delivered
          </label>
        </div>
  
        <label for="rows">Rows</label>
        <select v-model="filters.rows">
          <option v-for="n in [10, 20, 50]" :key="n" :value="n">{{ n }}</option>
        </select>
  
        <button @click="filterResults">Show Results</button>
      </div>
  
      <table>
        <thead>
          <tr>
            <th>To</th>
            <th>From</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Date Received</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="mail in filteredMails" :key="mail.id">
            <td>{{ mail.to }}</td>
            <td>{{ mail.from }}</td>
            <td>{{ mail.priority }}</td>
            <td>{{ mail.status }}</td>
            <td>{{ mail.dateReceived }}</td>
          </tr>
        </tbody>
      </table>
      <div class="pagination">
        Results {{ startItem }} - {{ endItem }} out of {{ mails.length }}
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        filters: {
          dateFrom: '',
          dateTo: '',
          priority: ["Very High", "High", "Normal", "Low", "Very Low"],
          status: ["Undelivered", "Delivered"],
          rows: 10,
        },
        priorities: ["Very High", "High", "Normal", "Low", "Very Low"],
        mails: [
          { id: 1, to: "Nino Balboa", from: "Metrobank", priority: "Very High", status: "Delivered", dateReceived: "Mar 2, 2017 02:39 PM" },
          { id: 2, to: "Ralph Lester Nebrida", from: "Landbank of the Philippines", priority: "Very High", status: "Delivered", dateReceived: "Jan 18, 2017 09:45 AM" },
          { id: 3, to: "Joshep Almenario", from: "PAG-IBIG", priority: "Normal", status: "Delivered", dateReceived: "Mar 4, 2017 05:10 AM" },
          { id: 4, to: "Rose Ann Apelenia", from: "Metrobank", priority: "Normal", status: "Delivered", dateReceived: "Mar 2, 2017 02:40 PM" },
          // More entries can be added here
        ],
      };
    },
    computed: {
      filteredMails() {
        // Filter mails based on the selected filters
        return this.mails
          .filter(mail => {
            const mailDate = new Date(mail.dateReceived);
            const fromDate = this.filters.dateFrom ? new Date(this.filters.dateFrom) : null;
            const toDate = this.filters.dateTo ? new Date(this.filters.dateTo) : null;
            return (
              (!fromDate || mailDate >= fromDate) &&
              (!toDate || mailDate <= toDate) &&
              this.filters.priority.includes(mail.priority) &&
              this.filters.status.includes(mail.status)
            );
          })
          .slice(0, this.filters.rows);
      },
      startItem() {
        return this.filteredMails.length > 0 ? 1 : 0;
      },
      endItem() {
        return this.filteredMails.length;
      },
    },
    methods: {
      filterResults() {
        // Handle filtering logic if needed
        console.log('Filters applied:', this.filters);
      },
    },
  };
  </script>
  
  <style scoped>
.mails-list {
  max-width: 1200px;
  margin: auto;
  padding: 20px;
  background-color: #f9f9f9;
}

h2 {
  color: #003366; /* Dark blue for headings */
}

.filters {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 20px;
  background-color: #e0e0e0; /* Light gray for filters background */
  padding: 15px;
  border-radius: 5px;
}

.filters label {
  color: #003366; /* Dark blue for labels */
  font-weight: bold;
}

.priority-filters,
.status-filters {
  display: flex;
  gap: 10px;
}

button {
  background-color: #003366; /* Dark blue button background */
  color: #fff;
  padding: 5px 10px;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}

button:hover {
  background-color: #00509e; /* Slightly brighter blue on hover */
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

table th {
  background-color: #003366; /* Dark blue for header background */
  color: #fff; /* White text for header */
  padding: 10px;
  text-align: left;
}

table td {
  padding: 10px;
  border: 1px solid #ddd;
}

.pagination {
  margin-top: 10px;
  font-size: 0.9em;
  color: #666; /* Gray text for pagination info */
}
</style>
