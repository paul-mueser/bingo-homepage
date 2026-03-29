<template>
  <DesktopContentView v-if="!mobile" :key="this.currentGameId" :gameId="this.currentGameId"/>
  <MobileContentView v-if="mobile" :key="this.currentGameId" :gameId="this.currentGameId"/>
</template>

<script setup>
import { useDisplay } from 'vuetify';

const { mobile } = useDisplay();

const props = defineProps({
  gameId: String
});
</script>

<script>
  import DesktopContentView from '@/views/DesktopContentView.vue';
  import MobileContentView from '@/views/MobileContentView.vue';

  export default {
    name: 'BingoView',
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
