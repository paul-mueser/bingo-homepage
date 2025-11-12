<template>
  <div class="container">
    <div class="content">
      <button @click="toggleEventList" :disabled="currentComponent==='EventList'">Events</button>
      <button @click="toggleBingo" :disabled="currentComponent==='BingoBoard'&&user==='Julia'">Julia</button>
      <button @click="toggleBingo" :disabled="currentComponent==='BingoBoard'&&user==='Pia'">Pia</button>
      <button @click="toggleBingo" :disabled="currentComponent==='BingoBoard'&&user==='Moritz'">Moritz</button>
      <button @click="toggleBingo" :disabled="currentComponent==='BingoBoard'&&user==='Paul'">Paul</button>
    </div>
    <div>
      <EventList v-if="currentComponent==='EventList'"/>
      <BingoBoard v-if="currentComponent==='BingoBoard'" :user="user"/>
    </div>
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
      this.currentComponent = "";
      const buttonText = event.target.innerText;
      setTimeout(() => {
        this.user = buttonText;
        this.currentComponent = "BingoBoard";
      }, 50);
    }
  }
}
</script>

<style scoped>
.content {
  margin-left: auto;
  margin-right: auto;
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  white-space: nowrap;
}

.game {
  outline-color: var(--text-color-highlight);
}
</style>
