<template>
  <div class="sidebar p-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <router-link class="nav-link" to="/admin-dashboard">
          <i class="bi bi-house-door-fill me-2"></i> Dashboard
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/admin-dashboard/scan-document">
          <i class="bi bi-printer me-2"></i> Scan Document
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/admin-dashboard/upload-document">
          <i class="bi bi-upload me-2"></i> Upload Document
        </router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/admin-dashboard/search-document">
          <i class="bi bi-search me-2"></i> Search Document
        </router-link>
      </li>
      <li class="nav-item" @click.prevent="toggleDocumentsSubMenu">
        <a class="nav-link d-flex align-items-center">
          <i class="bi bi-folder2-open me-2"></i>
          <span>Documents</span>
          <i :class="['bi', isDocumentsSubMenuOpen ? 'bi-caret-down-fill' : 'bi-caret-left-fill', 'ms-auto']"></i>
        </a>
        <transition name="slide-fade">
          <ul v-show="isDocumentsSubMenuOpen" class="nav flex-column ms-3 submenu">
            <li class="nav-item">
              <router-link class="nav-link" to="/admin-dashboard/documents/travel-order">
                <i class="bi bi-file-earmark-text me-2"></i> Travel Order
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/admin-dashboard/documents/office-order">
                <i class="bi bi-file-earmark-text me-2"></i> Office Order
              </router-link>
            </li>
          </ul>
        </transition>
      </li>
      <li class="nav-item" @click.prevent="toggleEmployeesSubMenu">
        <a class="nav-link d-flex align-items-center">
          <i class="bi bi-people-fill me-2"></i>
          <span>Employees</span>
          <i :class="['bi', isEmployeesSubMenuOpen ? 'bi-caret-down-fill' : 'bi-caret-left-fill', 'ms-auto']"></i>
        </a>
        <transition name="slide-fade">
          <ul v-show="isEmployeesSubMenuOpen" class="nav flex-column ms-3 submenu">
            <li class="nav-item">
              <router-link class="nav-link" to="/admin-dashboard/employee/add">
                <i class="bi bi-person-plus-fill me-2"></i> Add New Employee
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/admin-dashboard/employee/list">
                <i class="bi bi-list-ul me-2"></i> List of Employees
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/admin-dashboard/employee/deactivated">
                <i class="bi bi-person-dash-fill me-2"></i> Deactivated Employees
              </router-link>
            </li>
          </ul>
        </transition>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/admin-dashboard/mail">
          <i class="bi bi-envelope-fill me-2"></i> Mail
        </router-link>
      </li>
      <li class="nav-item" @click.prevent="toggleSettingsSubMenu">
        <a class="nav-link d-flex align-items-center">
          <i class="bi bi-gear-fill me-2"></i>
          <span>System Settings</span>
          <i :class="['bi', isSettingsSubMenuOpen ? 'bi-caret-down-fill' : 'bi-caret-left-fill', 'ms-auto']"></i>
        </a>
        <transition name="slide-fade">
          <ul v-show="isSettingsSubMenuOpen" class="nav flex-column ms-3 submenu">
            <li class="nav-item">
              <router-link class="nav-link" to="/admin-dashboard/createuser">
                Create New User
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="">
                Document Types
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="">
                University Position
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="">
                Academic Rank
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="">
                Settings
              </router-link>
            </li>
          </ul>
        </transition>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" to="/admin-dashboard/logout">
          <i class="fas fa-sign-out-alt me-2"></i> Logout
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'AdminSidebar',
  data() {
    return {
      isDocumentsSubMenuOpen: false,
      isEmployeesSubMenuOpen: false,
      isSettingsSubMenuOpen: false,
    };
  },
  methods: {
    toggleDocumentsSubMenu() {
      this.isDocumentsSubMenuOpen = !this.isDocumentsSubMenuOpen;
    },
    toggleEmployeesSubMenu() {
      this.isEmployeesSubMenuOpen = !this.isEmployeesSubMenuOpen;
    },
    toggleSettingsSubMenu() {
      this.isSettingsSubMenuOpen = !this.isSettingsSubMenuOpen;
    },
  },
};
</script>

<style scoped>
.sidebar {
  width: 250px;
}
.nav-link {
  display: block;
  padding: 10px 15px;
  text-decoration: none;
  color: inherit;
  transition: background-color 0.3s;
}
.nav-link:hover {
  background-color: #f0f0f0;
}
.nav-link .bi {
  font-size: 1.5rem;
}
.submenu {
  overflow: hidden;
}
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.3s ease;
}
.slide-fade-enter, .slide-fade-leave-to {
  max-height: 0;
  opacity: 0;
}
</style>
