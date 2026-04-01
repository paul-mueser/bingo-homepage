<template>
  <v-sheet>
    <form @submit.prevent="handleLogin">
      <v-text-field v-model="username.value.value" :error-messages="username.errorMessage.value" label="Username" autocomplete="username" @keydown.enter="focusNext('password')"></v-text-field>
      <v-text-field v-model="password.value.value" :error-messages="password.errorMessage.value" ref="passwordRef" label="Password" type="password" autocomplete="current-password" @keydown.enter="handleLogin"></v-text-field>
      <v-btn @click="handleLogin" base-color="primary" variant="elevated" style="margin-right:10px;">Login</v-btn>
      <v-btn @click="handleReset" base-color="secondary" variant="elevated">Clear</v-btn>
    </form>
  </v-sheet>
</template>

<script setup>
  import { ref } from 'vue';
  import { useField, useForm } from 'vee-validate';
  import { login } from '@/services/authService';
  import { useRouter } from 'vue-router';

  const passwordRef = ref(null);

  const router = useRouter();

  const { handleSubmit, handleReset } = useForm({
    validationSchema: {
      username: (value) => {
        if (!value) {
          return 'Username is required';
        }
        return true;
      },
      password: (value) => {
        if (!value) {
          return 'Password is required';
        }
        return true;
      },
    },
  });

  const username = useField('username');
  const password = useField('password');

  const handleLogin = handleSubmit(async (values) => {
    try {
      await login(values.username, values.password);
      window.dispatchEvent(new CustomEvent('auth-changed', { detail: { isAuthenticated: true } }));
      router.push('/');
    } catch (err) {
      if (err.response) {
        if (err.response.status === 404) {
          username.setErrors(['Invalid username']);
        } else if (err.response.status === 401) {
          password.setErrors(['Invalid password']);
        } else {
          password.errorMessage.value = 'Login failed';
        }
      } else {
        password.errorMessage.value = 'Login failed';
      }
    }
  });

  function focusNext(refName) {
    if (refName === 'password') {
      passwordRef.value?.focus();
    }
  }
</script>
  