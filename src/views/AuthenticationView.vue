<template>
  <v-container class="game" style="margin-top: 50px;">
    <v-btn base-color="primary" v-on:click="loginMode = true" :disabled="loginMode" style="margin-right: 10px;">Login</v-btn>
    <v-btn base-color="primary" v-on:click="loginMode = false" :disabled="!loginMode">Register</v-btn>
    <Login v-if="loginMode" style="margin-top: 20px;"></Login>
    <Register v-else style="margin-top: 20px;"></Register>
  </v-container>
</template>

<script>
// @ is an alias to /src
import Login from '@/components/Login.vue';
import Register from '@/components/Register.vue';

export default {
  name: 'AuthenticationView',
  components: {
    Login,
    Register
  },
  data() {
    return {
      loginMode: true
    }
  },
  methods: {
    _onRegisterSuccess() {
      this.loginMode = true;
    }
  },
  mounted() {
    window.addEventListener('register-success', this._onRegisterSuccess);
  },
  beforeUnmount() {
    window.removeEventListener('register-success', this._onRegisterSuccess);
  }
}
</script>
