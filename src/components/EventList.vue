<template>
    <div>
        <h1>Events</h1>
        <table>
            <thead>
                <tr>
                <th>Event Name</th>
                <th>Happened/Needed</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="event in events" :key="event.id">
                <td>{{ event.event }}</td>
                <td>{{ event.amountbased ? (event.amounthappened < event.amountneeded) : event.amounthappened + '/' + event.amountneeded }}</td>
                </tr>
            </tbody>
        </table>
    </div>
  </template>
  
  <script>
  import { fetchBingoEvents } from '../services/bingoService.js';
  
  export default {
    name: 'EventList',
    data() {
      return {
        events: []
      }
    },
    methods: {
      async fetchEvents() {
        try {
          const result = await fetchBingoEvents();
          this.events = result.data;
          console.log(this.events);
        } catch (err) {
          console.error('Error:', err);
        }
      },
    },
    mounted() {
        this.fetchEvents();
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
  