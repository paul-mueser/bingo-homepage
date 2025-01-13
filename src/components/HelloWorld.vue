<template>
  <div class="hello">
    <h1>{{ msg }}</h1>
    <form @submit.prevent="fetchEvents">
        <button type="submit">Events</button>
      </form>
  </div>
</template>

<script>
import { fetchBingoEvents } from '../services/bingoService';

export default {
  name: 'HelloWorld',
  props: {
    msg: String
  },
  methods: {
    async fetchEvents() {
      try {
        await fetchBingoEvents();
      } catch (err) {
        console.error('Error:', err);
      }
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
