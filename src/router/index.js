import { createRouter, createWebHistory } from 'vue-router'
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
    beforeEnter: async (to, from) => {
      const userStore = useUserStore();
      try {
        await logout();
      } catch (e) {
      }
      userStore.clearUser();
      return true;
    }
  },
  {
    name: 'Home',
    path: '/',
    component: HomeView,
    beforeEnter: async (to, from) => {
      const userStore = useUserStore();
      try {
        const response = await verifyToken();
        userStore.setUser(response.data.data);
        return true;
      } catch (error) {
        try {
          const response = await refreshToken();
          userStore.setUser(response.data.data);
          return true;
        } catch (refreshError) {
          userStore.clearUser();
          return { name: 'Login' };
        }
      }
    },
    props: true,
  },
  { 
    path: '/admin',
    component: AdminView,
    beforeEnter: async (to, from) => {
      const userStore = useUserStore();
      try {
        const response = await verifyToken();
        userStore.setUser(response.data.data);
        if (response.data.data.isAdmin) {
          return true;
        } else {
          window.alert('You must be an admin to access this area.');
          return { name: 'Home' };
        }
      } catch (error) {
        try {
          const refreshResponse = await refreshToken();
          userStore.setUser(refreshResponse.data.data);
          if (refreshResponse.data.data.isAdmin) {
            return true;
          } else {
            window.alert('You must be an admin to access this area.');
            return { name: 'Home' };
          }
        } catch (refreshError) {
          userStore.clearUser();
          return { name: 'Login' };
        }
      }
    },
  },
  {
    path: '/game/:gameId',
    component: ContentView,
    beforeEnter: async (to, from) => {
      const userStore = useUserStore();
      try {
        const response = await verifyToken();
        userStore.setUser(response.data.data);
        return true;
      } catch (error) {
        try {
          const refreshResponse = await refreshToken();
          userStore.setUser(refreshResponse.data.data);
          return true;
        } catch (refreshError) {
          userStore.clearUser();
          return { name: 'Login' };
        }
      }
    },
    props: true,
  },
  {
    path: '/games/:gameStatus',
    component: GamesView,
    beforeEnter: async (to, from) => {
      const userStore = useUserStore();
      try {
        const response = await verifyToken();
        userStore.setUser(response.data.data);
        return true;
      } catch (error) {
        try {
          const refreshResponse = await refreshToken();
          userStore.setUser(refreshResponse.data.data);
          return true;
        } catch (refreshError) {
          userStore.clearUser();
          return { name: 'Login' };
        }
      }
    },
    props: true,
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
