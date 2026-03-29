<template>
  <v-container class="contentWrap">
    <v-row>
      <v-col cols="1/2">
        <Leaderboard :gameId="this.gameId"/>
      </v-col>
      <v-col cols="1/2">
        <EventList :gameId="this.gameId"/>
      </v-col>
    </v-row>
    <v-row>
      <v-col v-for="user in participants" :key="user.userid">
        <v-btn @click="userid = user.userid">{{ user.username }}</v-btn>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <BingoBoard :gameId="this.gameId" :userid="this.userid"/>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
  import BingoBoard from '@/components/BingoBoard.vue';
  import EventList from '@/components/EventList.vue';
  import Leaderboard from '@/components/Leaderboard.vue';
  import { useUserStore } from '@/stores/user';
  import { fetchParticipants } from '@/services/bingoService.js';

  export default {
    name: 'DesktopContent',
    props: {
      gameId: String
    },
    components: {
      BingoBoard,
      EventList,
      Leaderboard
    },
    data() {
      return {
        userid: useUserStore().id,
        participants: []
      }
    },
    methods: {
      async prepareParticipants() {
        try {
          const result = await fetchParticipants(this.gameId);
          this.participants = result.data;
        } catch (err) {
          this.participants = [];
        }
      }
    },
    mounted() {
      this.prepareParticipants();
    }
  }
</script>
