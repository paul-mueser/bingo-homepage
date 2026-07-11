<template>
  <v-container class="contentWrap">
    <h1>Mobile Content | Game ID: {{ gameId }}</h1>
    <v-row v-if="value==='showBingoField'" class="justify-center mb-4 ml-1 mr-1">
      <v-col v-for="user in participants" :key="user.id" cols="auto">
        <v-btn @click="userid = user.id" class="bg-primary" :disabled="userid === user.id">{{ user.username }}</v-btn>
      </v-col>
    </v-row>
    <BingoBoard :key="userid" v-if="value==='showBingoField'" :gameId="gameId" :userid="userid"/>
    <EventList v-if="value==='showEvents'" :gameId="gameId"/>
    <Leaderboard v-if="value==='showLeaderboard'" :gameId="gameId"/>
  </v-container>
  <v-bottom-navigation v-model="value" color="primary" grow mode="shift" mandatory="true">
    <v-btn value="showBingoField">
      <v-icon icon="fa-solid fa-table-cells" end></v-icon>
      <span>Home</span>
    </v-btn>

    <v-btn value="showEvents">
      <v-icon icon="fa-solid fa-list" end></v-icon>
      <span>Events</span>
    </v-btn>

    <v-btn value="showLeaderboard">
      <v-icon icon="fa-solid fa-trophy" end></v-icon>
      <span>Leaderboard</span>
    </v-btn>
  </v-bottom-navigation>
</template>

<script setup>
import { ref } from 'vue';

const value = ref('showBingoField');
</script>

<script>
  import BingoBoard from '@/components/BingoBoard.vue';
  import EventList from '@/components/EventList.vue';
  import Leaderboard from '@/components/Leaderboard.vue';
  import { useUserStore } from '@/stores/user';
  import { fetchParticipants } from '@/services/bingoService.js';

  export default {
    name: 'MobileContent',
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

<style scoped>
  .contentWrap {
    margin-bottom: 56px;
  }
</style>
