<template>
  <v-sheet>
    <form @submit.prevent="handleRegister">
      <v-text-field v-model="username.value.value" :error-messages="username.errorMessage.value" label="Username" autocomplete="username" @keydown.enter="focusNext('password')"></v-text-field>
      <v-text-field v-model="password.value.value" :error-messages="password.errorMessage.value" ref="passwordRef" label="Password" type="password" autocomplete="new-password" @keydown.enter="focusNext('confirmPassword')"></v-text-field>
      <v-text-field v-model="confirmPassword.value.value" :error-messages="confirmPassword.errorMessage.value" label="Confirm Password" type="password" autocomplete="new-password" ref="confirmPasswordRef" @keydown.enter="focusNext('authCode')"></v-text-field>
      <v-text-field v-model="authCode.value.value" :error-messages="authCode.errorMessage.value" label="Authentication Code" autocomplete="off" ref="authCodeRef" @keydown.enter="handleRegister"></v-text-field>
      <v-btn @click="handleRegister" base-color="primary" style="margin-right:10px;">Register</v-btn>
      <v-btn @click="handleReset" base-color="secondary">Clear</v-btn>
    </form>
  </v-sheet>
</template>

<script setup>
  import { ref } from 'vue';
  import { useField, useForm } from 'vee-validate';
  import { register } from '@/services/authService';
  import { useRouter } from 'vue-router';

  const passwordRef = ref(null);
  const confirmPasswordRef = ref(null);
  const authCodeRef = ref(null);

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
      confirmPassword: (value) => {
        if (!value || value !== password.value.value) {
          return 'Passwords do not match';
        }
        return true;
      },
      authCode: (value) => {
        if (!value) {
          return 'Authentication code is required';
        }
        return true;
      },
    },
  });

  const username = useField('username');
  const password = useField('password');
  const confirmPassword = useField('confirmPassword');
  const authCode = useField('authCode');

  const handleRegister = handleSubmit(async (values) => {
    try {
      await register(values.username, values.password, values.authCode);
      router.push('/login');
      window.dispatchEvent(new CustomEvent('register-success'));
    } catch (err) {
      console.error('Registration failed', err);
      if (err.response) {
        if (err.response.status === 401) {
          authCode.setErrors(['Invalid authentication code']);
        } else if (err.response.status === 409) {
          username.setErrors(['Username already exists']);
        } else {
          authCode.errorMessage.value = 'Registration failed';
        }
      } else {
        authCode.errorMessage.value = 'Registration failed';
      }
    }
  });

  function focusNext(refName) {
    if (refName === 'password') {
      passwordRef.value?.focus();
    } else if (refName === 'confirmPassword') {
      confirmPasswordRef.value?.focus();
    } else if (refName === 'authCode') {
      authCodeRef.value?.focus();
    }
  }
</script>