<template>
  <Header ref="header"></Header>
  <div class="background">
    <div ref="body">
      <router-view/>
    </div>
  </div>
</template>

<script>
import Header from './components/Header.vue';

export default {
  components: {
    Header
  },
  methods: {
    headerHeight() {
      let header = this.$refs.header.$el.clientHeight;
      this.$refs.body.style.marginTop = (header + 16) + "px";
    }
  },
  mounted() {
    this.headerHeight();
  }
};
</script>

<style>
  :root {
    --background-color: rgb(31, 31, 31);
    --background-transparent: rgba(31, 31, 31, 0.911);
    --text-color: rgb(228, 226, 226);
    --text-color-highlight: rgb(0, 162, 255);
    --element-size: 30px;
  }

  body {
    height: 100%;
    width: 100%;
    min-height: 100%;
    display: flex;
    flex-direction: column;
    color: var(--text-color);
    margin: auto;
    font-family: 'Roboto';
  }

  .background {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    background-color: var(--background-color);
    transition: background-color .8s ease, color .8s ease;
  }

  .container {
    box-sizing: border-box;
    z-index: auto;
    display: flex;
    flex-direction: column;
    line-height: 24px;
    width: 44%;
    min-width: 800px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 10px;
    padding-right: 10px;
  }

  .findGoodName {
    margin-top: 4.5rem;
    margin-bottom: 2rem;
  }

  @media screen and (max-width: 800px) {
    div.container {
      width: 100%;
      min-width: 100%;
      margin-left: .5rem;
      margin-right: 1rem;
    }
  }

  .highlight {
    color: var(--text-color-highlight);
    text-decoration: none;
    margin: 0;
  }

  .highlight:hover {
    text-decoration: underline;
  }
</style>
