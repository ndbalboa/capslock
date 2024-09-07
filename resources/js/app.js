import './bootstrap';

import { createApp } from 'vue';
import app from './components/app.vue';
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

createApp(app).use(router).mount('#app');

