import { createRouter, createWebHashHistory } from 'vue-router'
import { verifyToken, refreshToken, logout } from '@/services/authService.js';
import AuthenticationView from '@/views/AuthenticationView.vue';
import HomeView from '@/views/HomeView.vue';
import AdminView from '@/views/AdminView.vue';
import ContentView from '@/views/ContentView.vue';
import GamesView from '@/views/GamesView.vue';
import { useUserStore } from '@/stores/user.js';

const routes = [
  { 
    name: 'Login',
    path: '/login',
    component: AuthenticationView,
    beforeEnter: async (to, from, next) => {
      const userStore = useUserStore();
      try {
        await logout();
      } catch (e) {
      }
      userStore.clearUser();
      next();
    }
  },
  {
    path: '/',
    component: HomeView,
    beforeEnter: async (to, from, next) => {
      const userStore = useUserStore();
      try {
        const response = await verifyToken();
        userStore.setUser(response.data.data);
        next();
      } catch (error) {
        try {
          const response = await refreshToken();
          userStore.setUser(response.data.data);
          next();
        } catch (refreshError) {
          userStore.clearUser();
          next('/login');
        }
      }
    },
    props: true,
  },
  { 
    path: '/admin',
    component: AdminView,
    beforeEnter: async (to, from, next) => {
      const userStore = useUserStore();
      try {
        const response = await verifyToken();
        userStore.setUser(response.data);
        if (response.data.isAdmin) {
          next();
        } else {
          window.alert('You must be an admin to access this area.');
          next('/');
        }
      } catch (error) {
        try {
          const refreshResponse = await refreshToken();
          userStore.setUser(refreshResponse.data);
          if (refreshResponse.data.isAdmin) {
            next();
          } else {
            window.alert('You must be an admin to access this area.');
            next('/');
          }
        } catch (refreshError) {
          userStore.clearUser();
          next('/login');
        }
      }
    },
  },
  {
    path: '/game/:gameId',
    component: ContentView,
    beforeEnter: async (to, from, next) => {
      const userStore = useUserStore();
      try {
        const response = await verifyToken();
        userStore.setUser(response.data.data);
        next();
      } catch (error) {
        try {
          const refreshResponse = await refreshToken();
          userStore.setUser(refreshResponse.data);
          next();
        } catch (refreshError) {
          userStore.clearUser();
          next('/login');
        }
      }
    },
    props: true,
  },
  {
    path: '/games/:gameStatus',
    component: GamesView,
    beforeEnter: async (to, from, next) => {
      const userStore = useUserStore();
      try {
        const response = await verifyToken();
        userStore.setUser(response.data.data);
        next();
      } catch (error) {
        try {
          const refreshResponse = await refreshToken();
          userStore.setUser(refreshResponse.data);
          next();
        } catch (refreshError) {
          userStore.clearUser();
          next('/login');
        }
      }
    },
    props: true,
  }
];

const router = createRouter({
  history: createWebHashHistory(process.env.BASE_URL),
  routes
})

export default router
