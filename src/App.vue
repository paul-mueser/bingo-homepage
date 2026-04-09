<template>
  <v-app :style="{background: $vuetify.theme.themes[theme].background}">
    <TopNavView v-if="this.isAuthenticated" :key="this.isAuthenticated"></TopNavView>
    <div ref="body">
      <router-view/>
    </div>
  </v-app>
</template>

<script>
  import TopNavView from '@/views/TopNavView.vue';
  import { verifyToken, refreshToken } from '@/services/authService.js';

  export default {
  data() {
    return {
      isAuthenticated: false
    };
  },
  components: {
    TopNavView
  },
  computed: {
    theme() {
      return (this.$vuetify.theme.dark) ? 'dark' : 'light';
    }
  },
  methods: {
      _onAuthChanged(e) {
        if (e && e.detail && typeof e.detail.isAuthenticated !== 'undefined') {
          this.isAuthenticated = e.detail.isAuthenticated;
        } else {
          this.checkAuth();
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
      }
  },
  mounted() {
    this.checkAuth();
    window.addEventListener('auth-changed', this._onAuthChanged);
  },
  beforeUnmount() {
    window.removeEventListener('auth-changed', this._onAuthChanged);
  },
  watch: {
    '$route.name' (name) {
      if (name === 'Login') {
        this.checkAuth();
      }
    }
  }
  };
</script>

<style>
  :root {
    --background-color: rgb(31, 31, 31);
    --background-transparent: rgba(31, 31, 31, 0.911);
    --text-color: rgb(255, 255, 255);
    --text-color-highlight: rgb(0, 162, 255);
    --text-color-highlight-secondary: rgb(84, 182, 178);
    --element-size: 30px;
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

  .contentWrap {
    margin-top: 80px;
  }

  .content {
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    white-space: nowrap;
  }

  .game {
    outline-style: solid;
    outline-color: var(--text-color-highlight);
    outline-offset: 4px;
    margin-bottom: 1em;
    align-items: center;
  }

  .game-title {
		cursor: pointer;
		user-select: none;
		margin: 0;
	}

  .caret {
		display: inline-block;
		width: 1.1em;
		text-align: center;
		margin-right: 0.25em;
	}

  .not-done {
	  background-color: red;
	}

	.done {
		background-color: green;
	}

	.impossible {
    background-color: black;
  }

  .v-btn.bg-primary {
    background-color: var(--text-color-highlight);
    color: var(--text-color);
  }

  .v-btn.bg-secondary {
    background-color: var(--text-color-highlight-secondary);
    color: var(--text-color);
  }

  .v-divider.black {
    color: var(--background-color);
  }
</style>
