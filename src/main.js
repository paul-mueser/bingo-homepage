import { createApp } from 'vue'
import App from './App.vue'
import { createVuetify } from 'vuetify'
import { aliases, fa } from 'vuetify/iconsets/fa-svg'
import 'vuetify/styles'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import router from './router'

import './assets/sass/style.scss'
import 'vuetify/styles'

import { library } from '@fortawesome/fontawesome-svg-core'
import { far } from '@fortawesome/free-regular-svg-icons'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'

library.add(far, fas, fab)

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

const app = createApp(App)

app.component('font-awesome-icon', FontAwesomeIcon)

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'fa',
        aliases,
        sets: {
            fa,
        },
    },
    theme: {
        defaultTheme: 'dark',
        themes: {
            dark: {
                colors: {
                    background: 'rgb(31, 31, 31)',
                    surface: 'rgb(33, 33, 33)',
                    primary: 'rgb(0, 162, 255)',
                    secondary: 'rgb(84, 182, 178)',
                    "on-background": 'rgb(255, 255, 255)',
                    "on-surface": 'rgb(255, 255, 255)',
                    "on-primary": 'rgb(255, 255, 255)',
                    "border-color": 'rgb(255, 255, 255)',
                    "border-opacity": '0.12',
                    "shadow-color": 'rgb(0, 0, 0)',
                    "high-emphasis-opacity": '1',
                }
            }
        }
    }
})

app.use(router).use(vuetify).use(createPinia().use(piniaPluginPersistedstate)).mount('#app')
