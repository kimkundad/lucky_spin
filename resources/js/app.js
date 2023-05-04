import './bootstrap';
import '../sass/app.scss'
import Router from '@/router'
import store from '@/store'
import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:8000/api/';
axios.defaults.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

import { createApp } from 'vue/dist/vue.esm-bundler';

const app = createApp({})
app.use(Router)
app.use(store)
app.mount('#app')