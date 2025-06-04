<template>
    <div class="container">
      <h2>Register</h2>
      <form @submit.prevent="handleRegister">
        <div>
          <input v-model="username" type="text" placeholder="Username" required />
        </div>
        <div>
          <input v-model="password" type="password" placeholder="Password" required />
        </div>
        <div>
          <input v-model="confirmPassword" type="password" placeholder="Confirm Password" required />
        </div>
        <div>
          <input v-model="authCode" type="text" placeholder="Authentication Code" required />
        </div>
        <button type="submit">Register</button>
      </form>
    </div>
  </template>
  
  <script>
  import { register } from '../services/authService';
  
  export default {
    data() {
      return {
        username: '',
        password: '',
        confirmPassword: '',
        authCode: '',
      };
    },
    methods: {
      async handleRegister() {
        if (this.password !== this.confirmPassword) {
          console.error('Passwords do not match');
          return;
        }
        try {
          await register(this.username, this.password, this.authCode);
          this.$router.push('/login');
        } catch (err) {
          console.error('Registration failed');
        }
      },
    },
  };
  </script>
  