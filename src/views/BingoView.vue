<template>
  <div class="home">
    <button @click="toggleEventList">Events</button>
    <EventList v-if="showEventList"/>
  </div>
</template>

<script>
// @ is an alias to /src
import EventList from '@/components/EventList.vue';

export default {
  name: 'BingoView',
  components: {
    EventList
  },
  data() {
    return {
      showEventList: false
    }
  },
  methods: {
    toggleEventList() {
      this.showEventList = !this.showEventList;
    }
  }
}
</script>
