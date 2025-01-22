import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import "bootstrap";
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap-icons/font/bootstrap-icons.css";

import App from './App.vue'
import router from './router'
import NavBar from "./components/NavBar.vue"
import Footer from './components/Footer.vue'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.component('nav-bar', NavBar)
app.component('footers', Footer)

app.mount('#app')