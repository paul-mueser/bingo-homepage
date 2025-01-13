<template>
    <div>
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
      };
    },
    methods: {
      async handleRegister() {
        if (this.password !== this.confirmPassword) {
          console.error('Passwords do not match');
          return;
        }
        try {
          await register(this.username, this.password);
          this.$router.push('/login'); // Navigate to login page
        } catch (err) {
          console.error('Registration failed:', err);
        }
      },
    },
  };
  </script>
  