<template>
  <Header ref="header"></Header>
  <div class="background">
    <div ref="scaleWrap" :style="scaleStyle">
      <div ref="body">
        <router-view/>
      </div>
    </div>
  </div>
</template>

<script>
import Header from './components/Header.vue';

export default {
  components: {
    Header
  },
  data() {
    return {
      DESIGN_WIDTH: 800,
      scale: 1,
      marginLeft: 'auto'
    }
  },
  computed: {
    scaleStyle() {
      return {
        transform: `scale(${this.scale})`,
        transformOrigin: 'top left',
        width: this.DESIGN_WIDTH + 'px',
        marginLeft: this.marginLeft,
        marginRight: 'auto',
        willChange: 'transform'
      };
    }
  },
  methods: {
    updateScale() {
      this.scale = Math.min(document.documentElement.clientWidth / this.DESIGN_WIDTH, 1);
      this.marginLeft = this.scale < 1 ? '0' : 'auto';
    },

    headerHeight() {
      let header = this.$refs.header.$el.clientHeight;
      this.$refs.body.style.marginTop = (header + 16) + "px";
    }
  },
  mounted() {
    this.updateScale();
    window.addEventListener('resize', this.updateScale);
    this.headerHeight();
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.updateScale);
  },
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

  .content {
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    white-space: nowrap;
  }

  @media screen and (max-width: 800px) {
    div.container {
      width: 100%;
      min-width: 100%;
      margin-left: 0px;
      margin-right: 1rem;
    }
  }

  .game {
    outline-style: solid;
    outline-color: var(--text-color-highlight);
    outline-offset: 4px;
    margin-bottom: 1em;
    align-items: center;
  }

  .game-title {
		cursor: pointer;
		user-select: none;
		margin: 0;
	}

  .caret {
		display: inline-block;
		width: 1.1em;
		text-align: center;
		margin-right: 0.25em;
	}

  button {
    cursor: pointer;
    user-select: none;
    background-color: var(--text-color);
    color: var(--background-color);
    border: 1px solid var(--text-color-highlight);
    transition: background-color .4s ease, color .4s ease, border-color .4s ease;
    border-radius: 4px;
    margin-left: 2px;
    margin-right: 2px;
  }

  button:hover {
    cursor: pointer;
    background-color: var(--text-color-highlight);
    color: var(--text-color)
  }

  button:disabled {
    cursor: default;
    background-color: gray;
    color: var(--background-color);
  }
</style>
