import { createRouter, createWebHashHistory } from 'vue-router'
import { verifyToken, refreshToken } from '../services/authService.js';
import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import BingoView from '../views/BingoView.vue';
import AdminView from '../views/AdminView.vue';

const routes = [
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  {
    path: '/',
    component: BingoView,
    beforeEnter: async (to, from, next) => {
      try {
        await verifyToken();
        next();
      } catch (error) {
        try {
          await refreshToken();
          next();
        } catch (refreshError) {
          next('/login');
        }
      }
    },
  },
  { 
    path: '/admin',
    component: AdminView,
    beforeEnter: async (to, from, next) => {
      try {
        const response = await verifyToken();
        if (response.data.isAdmin) {
          next();
        } else {
          next('/');
        }
      } catch (error) {
        try {
          const refreshResponse = await refreshToken();
          if (refreshResponse.data.isAdmin) {
            next();
          } else {
            next('/');
          }
        } catch (refreshError) {
          next('/login');
        }
      }
    },
  },
];

const router = createRouter({
  history: createWebHashHistory(process.env.BASE_URL),
  routes
})

export default router
