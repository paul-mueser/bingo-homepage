<template>
    <v-container>
      <h2>Register</h2>
      <form @submit.prevent="handleRegister">
        <div>
          <input v-model="username" type="text" autocomplete="username" placeholder="Username" required />
        </div>
        <div>
          <input v-model="password" type="password" autocomplete="new-password" placeholder="Password" required />
        </div>
        <div>
          <input v-model="confirmPassword" type="password" autocomplete="new-password" placeholder="Confirm Password" required />
        </div>
        <div>
          <input v-model="authCode" type="text" autocomplete="off" placeholder="Authentication Code" required />
        </div>
        <button type="submit">Register</button>
      </form>
    </v-container>
  </template>
  
  <script>
  import { register } from '@/services/authService';
  
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
  