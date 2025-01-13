<template>
    <div class="container">
      <h2>Login</h2>
      <form @submit.prevent="handleLogin">
        <div>
          <input v-model="username" type="text" placeholder="Username" required />
        </div>
        <div>
          <input v-model="password" type="password" placeholder="Password" required />
        </div>
        <button type="submit">Login</button>
      </form>
    </div>
  </template>
  
  <script>
  import { login } from '../services/authService';
  
  export default {
    data() {
      return {
        username: '',
        password: '',
      };
    },
    methods: {
      async handleLogin() {
        try {
          await login(this.username, this.password);
          this.$router.push('/');
        } catch (err) {
          console.error('Login failed:', err);
        }
      },
    },
  };
  </script>
  