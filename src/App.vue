<template>
  <div class="background">
    <header>
      <nav>
        <div class="container">
          <div class="content" style="font-size: 2rem;">
            <router-link to="/login">Login</router-link>
            <a> | </a>
            <router-link to="/register">Register</router-link>
            <a> | </a>
            <router-link to="/" @click="logout">Logout</router-link>
          </div>
        </div>
      </nav>
    </header>
    <router-view/>
  </div>
</template>

<script>
import { logout } from './services/authService.js';

export default {
  methods: {
    async logout() {
      try {
        await logout();
        //this.$router.push('/login');
      } catch (error) {
        console.error('Logout failed', error);
      }
    }
  }
};
</script>

<style>
:root {
  --background-color: rgb(31, 31, 31);
  --background-transparent: rgba(31, 31, 31, 0.911);
  --text-color: rgb(228, 226, 226);
  --text-color-highlight: rgb(0, 162, 255);
  --element-size: 30px;
}

header {
  position: fixed;
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

body {
  height: 100%;
  width: 100%;
  min-height: 100%;
  display: flex;
  flex-direction: column;
  color: var(--text-color);
  margin: auto;
  font-family: 'Roboto';
}

.background {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: var(--background-color);
  transition: background-color .8s ease, color .8s ease;
}

.container {
  box-sizing: border-box;
  z-index: auto;
  display: flex;
  flex-direction: column;
  line-height: 24px;
  width: 44%;
  min-width: 800px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 10px;
  padding-right: 10px;
}

.findGoodName {
  margin-top: 4.5rem;
  margin-bottom: 2rem;
}

@media screen and (max-width: 800px) {
  div.container {
    width: 100%;
    min-width: 100%;
    margin-left: .5rem;
    margin-right: 1rem;
  }
}

.content {
  margin-left: -10px;
  margin-right: -10px;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  white-space: nowrap;
}

.highlight {
  color: var(--text-color-highlight);
  text-decoration: none;
  margin: 0;
}

.highlight:hover {
  text-decoration: underline;
}

.router-link-active {
  color: var(--text-color-highlight);
}

a {
  color: var(--text-color);
  text-decoration: none;
  margin: 0 10px;
  transition: background-color .8s ease, color .8s ease;
}

a:hover {
  color: var(--text-color-highlight);
}
</style>
