import { createRouter, createWebHistory } from 'vue-router';

import login from '../components/auth/login.vue';
import AdminLayout from '../components/AdminLayout.vue';
import SecretaryLayout from '../components/SecretaryLayout.vue'; // For Secretary role
import UserLayout from '../components/UserLayout.vue';
import AdminDashboard from '../components/AdminDashboard.vue';
import SecretaryDashboard from '../components/SecretaryDashboard.vue'; // Secretary dashboard
import UserDashboard from '../components/UserDashboard.vue';
import UploadDocument from '../components/UploadDocument.vue';
import SearchDocument from '../components/SearchDocument.vue';
import DocumentsTravelOrder from '../components/DocumentsTravelOrder.vue';
import DocumentsOfficeOrder from '../components/DocumentsOfficeOrder.vue';
import DocumentsSpecialOrder from '../components/DocumentsSpecialOrder.vue';
import AddNewEmployee from '../components/AddNewEmployee.vue';
import ListOfEmployee from '../components/ListOfEmployee.vue';
import Mail from '../components/Mail.vue';
import Settings from '../components/Settings.vue';
import UserProfile from '../components/UserProfile.vue';
import CreateUserAccount from '../components/CreateUserAccount.vue';
import EmployeeInformation from '../components/EmployeeInformation.vue';
import EmployeeDocuments from '../components/EmployeeDocuments.vue';
import ChangeCredentials from '../components/ChangeCredentials.vue';
import EmployeeDetails from '../components/EmployeeDetails.vue';
import Logout from '../components/Logout.vue';
import DeactivatedEmployees from '../components/DeactivatedEmployees.vue';
import ScanDocument from '../components/ScanDocument.vue';
import AutoFill from '../components/AutoFill.vue';
import DocumentDetails from '../components/DocumentDetails.vue';

function isAuthenticated() {
  return !!localStorage.getItem('token');
}

function getUserRole() {
  const user = JSON.parse(localStorage.getItem('user'));
  return user ? user.role : null;
}

const routes = [
  { path: '/', component: login },
  
  // Admin Dashboard routes
  {
    path: '/admin-dashboard',
    component: AdminLayout,
    beforeEnter: (to, from, next) => {
      if (!isAuthenticated() || getUserRole() !== 'admin') {
        return next('/');
      }
      next();
    },
    children: [
      { path: '', component: AdminDashboard },
      { path: 'upload-document', component: UploadDocument },
      { path: 'scan-document', component: ScanDocument },
      { path: 'autofill', name: 'Autofill', component: AutoFill, props: true },
      { path: 'search-document', component: SearchDocument },
      { path: 'documents/travel-order', component: DocumentsTravelOrder },
      { path: 'documents/office-order', component: DocumentsOfficeOrder },
      { path: 'documents/special-order', component: DocumentsSpecialOrder },
      { path: 'documents/:id', name: 'DocumentDetails', component: DocumentDetails, props: true },
      { path: 'employee/add', component: AddNewEmployee },
      { path: 'employee/list', component: ListOfEmployee },
      { path: 'employee/deactivated', component: DeactivatedEmployees },
      { path: 'employee/:id', name: 'EmployeeInformation', component: EmployeeInformation },
      { path: 'createuser', component: CreateUserAccount },
      { path: '/admin/employee/:id', name: 'EmployeeDetails', component: EmployeeDetails },
      { path: '/employees/:id/documents', name: 'EmployeeDocuments', component: EmployeeDocuments, props: true },
      { path: 'mail', component: Mail },
      { path: 'settings', component: Settings },
      { path: 'logout', component: Logout },
    ],
  },

  // Secretary Dashboard routes
  {
    path: '/secretary-dashboard',
    component: SecretaryLayout,
    beforeEnter: (to, from, next) => {
      if (!isAuthenticated() || getUserRole() !== 'secretary') {
        return next('/');
      }
      next();
    },
    children: [
      { path: '', component: SecretaryDashboard },
      { path: 'upload-document', component: UploadDocument },
      { path: 'search-document', component: SearchDocument },
      { path: 'documents/travel-order', component: DocumentsTravelOrder },
      { path: 'documents/office-order', component: DocumentsOfficeOrder },
      { path: 'documents/:id', name: 'DocumentDetails', component: DocumentDetails, props: true },
      { path: 'mail', component: Mail },
      { path: 'settings', component: Settings },
      { path: 'logout', component: Logout },
    ],
  },

  // User Dashboard routes
  {
    path: '/user-dashboard',
    component: UserLayout,
    beforeEnter: (to, from, next) => {
      if (!isAuthenticated() || getUserRole() !== 'user') {
        return next('/');
      }
      next();
    },
    children: [
      { path: '', component: UserDashboard },
      { path: 'search-document', component: SearchDocument },
      { path: 'documents/travel-order', component: DocumentsTravelOrder },
      { path: 'documents/office-order', component: DocumentsOfficeOrder },
      { path: 'user-profile', component: UserProfile }, // User profile view and edit
      { path: 'mail', component: Mail },
      { path: 'settings', component: Settings },
      { path: 'settings/change-credentials', component: ChangeCredentials }, // Change credentials option
      { path: 'logout', component: Logout },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Global route guard to check authentication for routes that require it
router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated()) {
      return next('/');
    }
  }
  next();
});

export default router;
