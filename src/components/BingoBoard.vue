<template>
    <div>
      <h1>{{ user }}</h1>
      <p>Lorem ipsum</p>
    </div>
  </template>
  
  <script>
  import { fetchBingoBoard } from '../services/bingoService.js';
  
  export default {
    name: 'EventList',
    props: {
      user: String
    },
    methods: {
      async fetchBoard() {
        try {
          await fetchBingoBoard(this.user);
        } catch (err) {
          console.error('Error:', err);
        }
      },
    },
    mounted() {
        this.fetchBoard();
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
  