import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import './assets/sass/style.scss'

createApp(App).use(router).mount('#app')
