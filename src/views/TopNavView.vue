<template>
  <v-app-bar scrollBehavior="hide">
    <template v-slot:prepend>
      <v-app-bar-nav-icon @click="drawer = !drawer">
      </v-app-bar-nav-icon>
    </template>
    <v-app-bar-title @click="$router.push('/')" class="title">Bingo Homepage</v-app-bar-title>
  </v-app-bar>
  <v-navigation-drawer v-model="drawer" temporary>
    <v-list-item link to="/">Home</v-list-item>
    <v-divider class="mb-8"></v-divider>
    <v-divider class="mt-8"></v-divider>
    <v-list-item link to="/games/running">Running Games</v-list-item>
    <v-list-item link to="/games/finished">Finished Games</v-list-item>
    <v-list-item link to="/games/upcoming">Upcoming Games</v-list-item>
    <v-divider class="mb-8"></v-divider>
    <v-divider class="mt-8"></v-divider>
    <v-list-item link to="/admin">Admin</v-list-item>
    <v-list-item @click="initializeLogout">Logout</v-list-item>
  </v-navigation-drawer>
</template>

<script setup>
import { ref } from 'vue';

const drawer = ref(null);
</script>

<script>
import { logout } from '@/services/authService.js';
import { useUserStore } from '@/stores/user';

export default {
  name: 'TopNavView',
  methods: {
    async initializeLogout() {
      try {
        await logout();

        const userStore = useUserStore();
        userStore.clearUser();

        this.$router.push('/login');
      } catch (error) {
        console.error('Logout failed');
      }
      window.dispatchEvent(new CustomEvent('auth-changed', { detail: { isAuthenticated: false } }));
    },
  }
}
</script>

<style scoped>
.title {
  cursor: pointer;
  text-decoration: none;
  margin: 0 10px;
  transition: background-color .8s ease, color .8s ease;
}

.title:hover {
  color: var(--text-color-highlight);
}
</style>
