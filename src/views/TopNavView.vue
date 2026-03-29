<template>
  <v-app-bar scrollBehavior="hide">
    <template v-slot:prepend>
      <v-app-bar-nav-icon @click="drawer = !drawer">
      </v-app-bar-nav-icon>
    </template>
    <v-app-bar-title @click="$router.push('/')" class="title">{{ this.pageTitle }}</v-app-bar-title>
  </v-app-bar>
  <v-navigation-drawer v-model="drawer" temporary>
    <v-list-item link to="/" :title="Home">Home</v-list-item>
    <v-divider class="mb-8"></v-divider>
    <v-divider class="mt-8"></v-divider>
    <v-list-item link to="/games/upcoming">Upcoming Games</v-list-item>
    <v-list-item link to="/games/running">Running Games</v-list-item>
    <v-list-item link to="/games/finished">Finished Games</v-list-item>
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
import { fetchBingoGames } from '@/services/bingoService';
import { logout } from '@/services/authService.js';
import { useUserStore } from '@/stores/user';

export default {
  name: 'TopNavView',
  data() {
    return {
      upcomingGames: [],
      runningGames: [],
      finishedGames: [],
      pageTitle: 'Bingo Homepage',
    }
  },
  methods: {
    async fetchBingoGames() {
      try {
        const result = await fetchBingoGames();
        const bingoGames = result.data;
        this.upcomingGames = bingoGames.filter(game => game.status === 0);
        this.runningGames = bingoGames.filter(game => game.status === 1);
        this.finishedGames = bingoGames.filter(game => game.status === 2);
      } catch (e) {
      }
    },
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
  },
  mounted() {
    this.fetchBingoGames();
  },
}
</script>

<style scoped>
.header {
  position: fixed;
  z-index: 1000;
  width: 100%;
  background-color: var(--background-transparent);
  padding-top: .5rem;
  padding-bottom: .5rem;
  top: 0;
  height: fit-content;
  border-bottom: 1px solid var(--text-color); /* todo change to better boarder */
  color: var(--text-color);
  transition: background-color .8s ease, color .8s ease;
}

.router-link-active {
  color: var(--text-color-highlight);
}

.title {
  cursor: pointer;
  color: var(--text-color);
  text-decoration: none;
  margin: 0 10px;
  transition: background-color .8s ease, color .8s ease;
}

.title:hover {
  color: var(--text-color-highlight);
}
</style>
