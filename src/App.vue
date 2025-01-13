<template>
  <header>
    <nav>
      <router-link to="/login">Login</router-link>
      <a> | </a>
      <router-link to="/register">Register</router-link>
      <a> | </a>
      <a @click="logout">Logout</a>
    </nav>
  </header>
  <router-view/>
</template>

<script>
import { logout } from './services/authService.js';

export default {
  methods: {
    async logout() {
      try {
        await logout();
        this.$router.push('/login');
      } catch (error) {
        console.error('Logout failed', error);
      }
    }
  }
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  background-color: rgb(31, 31, 31);
}

nav {
  padding: 30px;
}

nav a {
  font-weight: bold;
  color: #2c3e50;
}

nav a.router-link-exact-active {
  color: #42b983;
}
</style>
