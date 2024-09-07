<template>
    <div id="app">
      <header class="header">
        <h2>Search Document</h2>
      </header>
      <div class="main-content">
        <div class="container mt-4">
          <div class="row">
            <div class="col-md-8">
              <div class="row">
                <div class="col">
                  <button class="btn btn-primary float-right mb-3" @click="showAdvancedSearch">Advanced Search</button>
                </div>
              </div>
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search..." v-model="searchQuery">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" @click="search">Search</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Advanced Search Modal -->
      <div class="modal fade" id="advancedSearchModal" tabindex="-1" role="dialog" aria-labelledby="advancedSearchModalLabel" aria-hidden="true" ref="advancedSearchModal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="advancedSearchModalLabel">Advanced Search</h5>
              <button type="button" class="close" @click="hideAdvancedSearch" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- Advanced Search Form -->
              <form @submit.prevent="performAdvancedSearch">
                <div class="form-group">
                  <label for="keyword">Document Type</label>
                  <input type="text" class="form-control" id="keyword" v-model="advancedSearchQuery.keyword">
                </div>
                <div class="form-group">
                  <label for="dateFrom">Date Issued</label>
                  <input type="date" class="form-control" id="dateFrom" v-model="advancedSearchQuery.dateFrom">
                </div>
                <div class="form-group">
                  <label for="documentNumber">Document Number</label>
                  <input type="text" class="form-control" id="documentNumber" v-model="advancedSearchQuery.documentNumber">
                </div>
                <div class="form-group">
                  <label for="employee">Employee</label>
                  <input type="text" class="form-control" id="employee" v-model="advancedSearchQuery.employee">
                </div>
                <div class="form-group">
                  <label for="subject">Subject</label>
                  <input type="text" class="form-control" id="subject" v-model="advancedSearchQuery.subject">
                </div>
                <div class="form-group">
                  <label for="from">From</label>
                  <input type="text" class="form-control" id="from" v-model="advancedSearchQuery.from">
                </div>
                <div class="form-group">
                  <label for="to">To</label>
                  <input type="text" class="form-control" id="to" v-model="advancedSearchQuery.to">
                </div>
                <!-- Add more fields as needed -->
                <button type="submit" class="btn btn-primary">Search</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

<script>
import { Modal } from 'bootstrap';
export default {
    name: 'SearchDocument',

    data() {
      return {
        searchQuery: '',
        advancedSearchQuery: {
          keyword: '',
          dateFrom: '',
          documentNumber: '',
          employee: '',
          subject: '',
          from: '',
          to: ''
        }
      };
    },
    methods: {
      search() {
        console.log('Search query:', this.searchQuery);
        // Implement your search logic here
      },
      showAdvancedSearch() {
        const modal = new Modal(this.$refs.advancedSearchModal);
        modal.show();
      },
      hideAdvancedSearch() {
        const modal = new Modal(this.$refs.advancedSearchModal);
        modal.hide();
      },
      performAdvancedSearch() {
        console.log('Advanced Search query:', this.advancedSearchQuery);
        // Implement your advanced search logic here
        this.hideAdvancedSearch();
      }
    }
  };
  </script>
  
  <style scoped>
  /* Add custom styles here if needed */
  </style>
  