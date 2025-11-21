<template>
  <div class="container">
    <div class="content">
      <button @click="toggleEventList" :disabled="currentComponent==='EventList'">Events</button>
      <button v-for="user in users" :key="user.username" @click="toggleBingo" :disabled="currentComponent==='BingoBoard'&&this.user===user.username">{{ user.username }}</button>
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
import { fetchUsers } from '../services/bingoService.js';

export default {
  name: 'BingoView',
  components: {
    EventList,
    BingoBoard
  },
  data() {
    return {
      users: [],
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
    },
    async prepareUsers() {
      try {
        const result = await fetchUsers();
        this.users = result.data;
      } catch (err) {
      }
    }
  },
  mounted() {
    this.prepareUsers();
  }
}
</script>

<style>
  .not-done {
	  background-color: red;
	}

	.done {
		background-color: green;
	}

	.impossible {
    background-color: black;
  }
</style>
