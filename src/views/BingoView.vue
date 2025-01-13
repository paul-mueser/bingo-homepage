<template>
  <div>
    <button @click="toggleEventList" :disabled="currentComponent==='EventList'">Events</button>
    <button @click="toggleBingo" :disabled="currentComponent==='BingoBoard'">Julia</button>
    <button @click="toggleBingo" :disabled="currentComponent==='BingoBoard'">Pia</button>
    <button @click="toggleBingo" :disabled="currentComponent==='BingoBoard'">Moritz</button>
    <button @click="toggleBingo" :disabled="currentComponent==='BingoBoard'">Paul</button>
    <EventList v-if="currentComponent==='EventList'"/>
    <BingoBoard v-if="currentComponent==='BingoBoard'" :user="user"/>
  </div>
</template>

<script>
// @ is an alias to /src
import EventList from '@/components/EventList.vue';
import BingoBoard from '@/components/BingoBoard.vue';

export default {
  name: 'BingoView',
  components: {
    EventList,
    BingoBoard
  },
  data() {
    return {
      currentComponent: "EventList",
      user: ""
    }
  },
  methods: {
    toggleEventList() {
      this.currentComponent = "EventList";
    },
    toggleBingo(event) {
      const buttonText = event.target.innerText;
      this.user = buttonText;
      this.currentComponent = "BingoBoard";
    }
  }
}
</script>
