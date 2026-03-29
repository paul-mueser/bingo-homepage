<template>
  <v-container class="contentWrap">
    <h1>Willkommen, {{ username }}!</h1>
  </v-container>
</template>

<script>
import { useUserStore } from '@/stores/user';

export default {
  name: 'HomeView',
  data() {
    return {
      username: useUserStore().username
    }
  },
}
</script>
