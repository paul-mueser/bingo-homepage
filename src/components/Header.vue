<template>
    <header>
        <nav>
            <div class="container">
                <div class="content" style="font-size: 2rem;">
                    <router-link v-if="isAuthenticated" to="/" class="highlight">Home</router-link>
                    <router-link v-if="!isAuthenticated" to="/login">Login</router-link>
                    <a> | </a>
                    <router-link v-if="!isAuthenticated" to="/register">Register</router-link>
                    <router-link v-if="isAuthenticated" to="/login" @click="logout">Logout</router-link>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
  import { verifyToken } from '../services/authService.js';
  import { logout } from '../services/authService.js';

  export default {
  data() {
    return {
      isAuthenticated: false
    };
  },
  methods: {
      async checkAuth() {
        try {
          await verifyToken();
          this.isAuthenticated = true;
        } catch (error) {
          this.isAuthenticated = false;
        }
      },
      async logout() {
      try {
          await logout();
          //this.$router.push('/login');
      } catch (error) {
          console.error('Logout failed', error);
      }
      }
  },
  mounted() {
    this.checkAuth();
  }
  };
</script>

<style scoped>
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

.content {
  margin-left: -10px;
  margin-right: -10px;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  white-space: nowrap;
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