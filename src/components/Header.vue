<template>
    <header>
        <nav>
            <div class="container">
                <div class="content" style="font-size: 2rem;">
                    <router-link v-if="isAuthenticated" to="/">Home</router-link>
                    <router-link v-if="!isAuthenticated" to="/login">Login</router-link>
                    <a> | </a>
                    <router-link v-if="isAuthenticated" to="/admin">Admin</router-link>
                    <router-link v-if="!isAuthenticated" to="/register">Register</router-link>
                    <a v-if="isAuthenticated"> | </a>
                    <router-link v-if="isAuthenticated" to="/login" @click="logout">Logout</router-link>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
  import { verifyToken, refreshToken } from '../services/authService.js';
  import { logout } from '../services/authService.js';

  export default {
  data() {
    return {
      isAuthenticated: false
    };
  },
  methods: {
      _onAuthChanged(e) {
        if (e && e.detail && typeof e.detail.isAuthenticated !== 'undefined') {
          this.isAuthenticated = e.detail.isAuthenticated;
        } else {
          this.isAuthenticated = true;
        }
      },
      async checkAuth() {
        try {
          await verifyToken();
          this.isAuthenticated = true;
        } catch (error) {
          try {
            await refreshToken();
            this.isAuthenticated = true;
          } catch (error) {
            this.isAuthenticated = false;
          }
        }
      },
      async logout() {
      try {
          await logout();
      } catch (error) {
          console.error('Logout failed');
      }
      this.isAuthenticated = false;
      window.dispatchEvent(new CustomEvent('auth-changed', { detail: { isAuthenticated: false } }));
      }
  },
  mounted() {
    this.checkAuth();
    window.addEventListener('auth-changed', this._onAuthChanged);
  }
  ,
  beforeUnmount() {
    window.removeEventListener('auth-changed', this._onAuthChanged);
  }
  };
</script>

<style scoped>
header {
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

.content {
  margin-left: -10px;
  margin-right: -10px;
}

/* Blue highlight for active/hovering page */
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