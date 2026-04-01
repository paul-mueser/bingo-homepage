<template>
  <DesktopContentView v-if="!mobile" :key="currentGameId" :gameId="currentGameId"/>
  <MobileContentView v-if="mobile" :key="currentGameId" :gameId="currentGameId"/>
</template>

<script setup>
import { useDisplay } from 'vuetify';

const { mobile } = useDisplay();
</script>

<script>
  import DesktopContentView from '@/views/DesktopContentView.vue';
  import MobileContentView from '@/views/MobileContentView.vue';

  export default {
    name: 'BingoView',
    props: {
      gameId: String
    },
    components: {
      DesktopContentView,
      MobileContentView
    },
    data() {
      return {
        currentGameId: this.gameId
      }
    },
    watch: {
      '$route.params.gameId': function(newGameId) {
        this.currentGameId = newGameId;
      }
    }
  }
</script>
