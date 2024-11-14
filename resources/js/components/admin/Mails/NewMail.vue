<template>
    <div><h1>Record Mail</h1></div>
    <div class="record-mail">
      <form @submit.prevent="recordMail">
        <div class="form-group">
          <label for="sender">Sender</label>
          <input
            type="text"
            id="sender"
            v-model="form.sender"
            placeholder="Enter sender's full name or company name"
            required
          />
        </div>
  
        <div class="form-group">
          <label for="receiver">Receiver</label>
          <select id="receiver" v-model="form.receiver" required>
            <option value="" disabled>-- select employee --</option>
            <option v-for="employee in employees" :key="employee.id" :value="employee.id">
              {{ employee.name }}
            </option>
          </select>
        </div>
  
        <div class="form-group">
          <label for="description">Description</label>
          <textarea
            id="description"
            v-model="form.description"
            placeholder="Enter keywords that best describe the mail."
          ></textarea>
        </div>
  
        <div class="form-group">
          <label for="priority">Priority</label>
          <select id="priority" v-model="form.priority" required>
            <option value="Normal">Normal</option>
            <option value="High">High</option>
            <option value="Low">Low</option>
          </select>
        </div>
  
        <div class="form-buttons">
          <button type="button" @click="clearForm">Clear Form</button>
          <button type="submit" class="record-btn">Record Mail</button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        form: {
          sender: '',
          receiver: '',
          description: '',
          priority: 'Normal',
        },
        employees: [
          { id: 1, name: 'John Doe' },
          { id: 2, name: 'Jane Smith' },
          // Add more employees as needed
        ],
      };
    },
    methods: {
      clearForm() {
        this.form.sender = '';
        this.form.receiver = '';
        this.form.description = '';
        this.form.priority = 'Normal';
      },
      recordMail() {
        // Logic to handle the mail record submission
        console.log('Mail recorded:', this.form);
        alert('Mail recorded successfully!');
        this.clearForm();
      },
    },
  };
  </script>
  
  <style scoped>
  .record-mail {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f9f9f9;
  }
  
  h2 {
    text-align: center;
    margin-bottom: 20px;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }
  
  .form-group input,
  .form-group select,
  .form-group textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  textarea {
    resize: vertical;
    height: 80px;
  }
  
  .form-buttons {
    display: flex;
    justify-content: space-between;
  }
  
  button {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  button[type="button"] {
    background-color: #f5f5f5;
  }
  
  .record-btn {
    background-color: #007bff;
    color: white;
  }
  
  button:hover {
    opacity: 0.9;
  }
  </style>
  