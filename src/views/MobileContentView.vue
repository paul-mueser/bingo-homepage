<template>
  <v-container class="contentWrap">
    <h1>Mobile Content | Game ID: {{ gameId }}</h1>
    <BingoBoard v-if="value==='showBingoField'" :gameId="gameId" :userid="userid"/>
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

  export default {
    name: 'MobileContent',
    components: {
      BingoBoard,
      EventList,
      Leaderboard
    },
    props: {
      gameId: String
    },
    data() {
      return {
        userid: useUserStore().id
      }
    }
  }
</script>

<style scoped>
  .contentWrap {
    margin-bottom: 56px;
  }
</style>
