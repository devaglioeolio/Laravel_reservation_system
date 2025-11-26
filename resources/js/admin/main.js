import '../../css/admin.css'
import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'
window.axios = axios

import App from './App.vue'
import Dashboard from './pages/Dashboard.vue'
import Facilities from './pages/Facilities.vue'

const routes = [
    { path: '/', component: Dashboard },
    { path: '/facilities', component: Facilities },
]

const router = createRouter({
    history: createWebHistory('/admin'),
    routes,
})

createApp(App).use(router).mount('#admin-app')
