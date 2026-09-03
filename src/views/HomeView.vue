<template>
  <v-container class="contentWrap">
    <h1>Willkommen {{ username }}, hier findest du deine Lesezeichen!</h1>
    <BookmarkedGames/>
    <h2>Reiche Vorschläge für neue Events ein</h2>
    <EventSubmissionForm/>
  </v-container>
</template>

<script>
import BookmarkedGames from '@/components/BookmarkedGames.vue';
import EventSubmissionForm from '@/components/EventSubmissionForm.vue';
import { useUserStore } from '@/stores/user';

export default {
  name: 'HomeView',
  components: {
    BookmarkedGames,
    EventSubmissionForm
  },
  data() {
    return {
      username: useUserStore().username
    }
  },
}
</script>
