<template>
  <v-container class="contentWrap">
    <h1>Willkommen {{ username }}, hier findest du deine Lesezeichen!</h1>
    <BookmarkedGames/>
  </v-container>
</template>

<script>
import BookmarkedGames from '@/components/BookmarkedGames.vue';
import { useUserStore } from '@/stores/user';

export default {
  name: 'HomeView',
  components: {
    BookmarkedGames
  },
  data() {
    return {
      username: useUserStore().username
    }
  },
}
</script>
