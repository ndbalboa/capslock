// Import Bootstrap and other required styles
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

import { createApp } from 'vue';
import App from './components/app.vue'; 
import router from './router';

createApp(App)
  .use(router) 
  .mount('#app'); 
