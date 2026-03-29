<template>
  <v-container>
    <div class="content">
      <v-btn v-on:click="loginMode = true">Login</v-btn>
      <v-btn v-on:click="loginMode = false">Register</v-btn>
    </div>
  </v-container>
  <Login v-if="loginMode"></Login>
  <Register v-else></Register>
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
  }
}
</script>
