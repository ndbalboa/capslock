<template>
    <div>
      <h2>Office Orders</h2>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'OfficeOrder',
    data() {
      return {
        officeOrders: [], // Array to hold existing office orders
        newOfficeOrder: { title: '', description: '' }, // Object for creating new office order
      };
    },
    methods: {
      async fetchOfficeOrders() {
        try {
          const response = await axios.get('/api/documents/office-order');
          this.officeOrders = response.data;
        } catch (error) {
          console.error('Error fetching office orders:', error);
        }
      },
      async createOfficeOrder() {
        try {
          const response = await axios.post('/api/documents/office-order', this.newOfficeOrder);
          this.officeOrders.push(response.data); // Add newly created order to list
          this.newOfficeOrder = { title: '', description: '' }; // Clear form fields
        } catch (error) {
          console.error('Error creating office order:', error);
        }
      },
      // Additional methods for updating, deleting orders can be added here
    },
    mounted() {
      this.fetchOfficeOrders(); // Fetch office orders when component is mounted
    }
  };
  </script>
  
  <style scoped>
  /* Add scoped styles as needed */
  </style>
  