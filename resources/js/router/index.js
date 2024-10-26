import { createRouter, createWebHistory } from 'vue-router';

import login from '../components/auth/login.vue';
import AdminLayout from '../components/admin/AdminLayout.vue';
import SecretaryLayout from '../components/secretary/SecretaryLayout.vue'; 
import UserLayout from '../components/user/UserLayout.vue';
import AdminDashboard from '../components/admin/AdminDashboard.vue';
import SecretaryDashboard from '../components/secretary/SecretaryDashboard.vue'; 
import UserDashboard from '../components/user/UserDashboard.vue';
import UploadDocument from '../components/admin/UploadDocument.vue';
import SearchDocument from '../components/admin/SearchDocument.vue';
import UserSearchDocument from '../components/user/UserSearchDocument.vue';
import DocumentsTravelOrder from '../components/admin/DocumentsTravelOrder.vue';
import DocumentsOfficeOrder from '../components/admin/DocumentsOfficeOrder.vue';
import DocumentsSpecialOrder from '../components/admin/DocumentsSpecialOrder.vue';
import AddNewEmployee from '../components/admin/AddNewEmployee.vue';
import ListOfEmployee from '../components/admin/ListOfEmployee.vue';
import Mail from '../components/admin/Mail.vue';
import Settings from '../components/Settings.vue';
import UserProfile from '../components/user/UserProfile.vue';
import CreateUserAccount from '../components/admin/CreateUserAccount.vue';
import EmployeeInformation from '../components/admin/EmployeeInformation.vue';
import EmployeeDocuments from '../components/admin/EmployeeDocuments.vue';
import ChangeCredentials from '../components/user/ChangeCredentials.vue';
import EmployeeDetails from '../components/admin/EmployeeDetails.vue';
import Logout from '../components/Logout.vue';
import DeactivatedEmployees from '../components/admin/DeactivatedEmployees.vue';
import ScanDocument from '../components/admin/ScanDocument.vue';
import AutoFill from '../components/admin/AutoFill.vue';
import DocumentDetails from '../components/admin/DocumentDetails.vue';
import UserTravelOrder from '../components/user/UserTravelOrder.vue';
import UserOfficeOrder from '../components/user/UserOfficeOrder.vue';
import UserSpecialOrder from '../components/user/UserSpecialOrder.vue';
import UserDocumentDetails from '../components/user/UserDocumentDetails.vue';

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
      { path: 'documents/:id', name: 'DocumentDetails', component: DocumentDetails},
      { path: 'employee/add', component: AddNewEmployee },
      { path: 'employee/list', component: ListOfEmployee },
      { path: 'employee/deactivated', component: DeactivatedEmployees },
      { path: 'employee/:id', name: 'EmployeeInformation', component: EmployeeInformation },
      { path: 'createuser', component: CreateUserAccount },
      { path: 'admin/employee/:id', name: 'EmployeeDetails', component: EmployeeDetails },
      { path: 'employees/:id/documents', name: 'EmployeeDocuments', component: EmployeeDocuments, props: true },
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
      { path: 'search-document', component: UserSearchDocument },
      { path: 'documents/:id', name: 'UserDocumentDetails', component: UserDocumentDetails},
      { path: 'documents/travel-order', component: UserTravelOrder },
      { path: 'documents/office-order', component: UserOfficeOrder },
      { path: 'documents/special-order', component: UserSpecialOrder },
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
