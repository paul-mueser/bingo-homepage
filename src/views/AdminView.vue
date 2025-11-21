<template>
  <div class="container">
    <h1>Create and edit games</h1>
    <h1>Create Game</h1>
    <form @submit.prevent="createGame">
      <div>
        <label for="game-name">Game Name:</label>
        <input
          type="text"
          id="game-name"
          v-model="newGameName"
          :placeholder="newGameError || 'Name'"
          :class="{ 'input-error': newGameError }"
          :aria-invalid="!!newGameError"
        />
      </div>
      <button type="submit">Create Game</button>
    </form>
    <h1>Running Games</h1>
    <div v-for="game in runningGames" :key="game.gameid" class="game">
      <h2 @click="toggleCollapse(game.gameid)" class="game-title"><span class="caret">{{ collapsedGames.get(game.gameid) ? '▸' : '▾' }}</span> {{ game.name }}</h2>
      <div v-show="!collapsedGames.get(game.gameid)">
        <h3>Game {{ game.name }} Controls</h3>
        <p>Number of events: {{ events.get(game.gameid) ? events.get(game.gameid).length : 0 }}</p>
        <button type="submit" @click="stopGame(game.gameid)">Stop Game</button>
      </div>
    </div>
    <h1>Upcoming Games</h1>
    <div v-for="game in upcomingGames" :key="game.gameid" class="game">
      <h2 @click="toggleCollapse(game.gameid)" class="game-title"><span class="caret">{{ collapsedGames.get(game.gameid) ? '▸' : '▾' }}</span> {{ game.name }}</h2>
      <div v-show="!collapsedGames.get(game.gameid)">
        <h3>Game {{ game.name }} Controls</h3>
        <!-- Admin controls for running games go here -->
        <!-- Things like count of events, players boards upload, event upload, start stop -->
        <p>Number of events: {{ events.get(game.gameid) ? events.get(game.gameid).length : 0 }}</p>
        <button type="submit" @click="startGame(game.gameid)">Start Game</button><br/><br/>
        <div>
          <label for="event-upload">Upload Events (CSV):</label>
          <input :ref="'eventUpload' + game.gameid" id="event-upload" type="file" accept=".csv"/>
          <button type="submit" @click="uploadEvents(game.gameid)">Upload Events</button>
        </div>
        <div>
          <label>Upload Bingo Board (CSV):</label>
          <select v-model="selectedPlayer">
            <option disabled value="">Please select a Player</option>
            <option v-for="p in players" :key="p.id" :value="p.id">
              {{ p.username }}
            </option>
          </select>
          <input :ref="'bingoBoardUpload' + game.gameid" id="bingo-board-upload" type="file" accept=".csv"/>
          <button type="submit" @click="uploadBingoBoard(game.gameid)" :disabled="this.selectedPlayer === ''">Upload Bingo Board</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { fetchBingoGames, createBingoGame, fetchBingoEvents, updateGameStatus, createBingoEvents, fetchUsers, createBingoBoard } from '../services/bingoService.js';
  export default {
    name: 'AdminView',
    data() {
      return {
        runningGames: [],
        upcomingGames: [],
        games: [],
        events: new Map(),
        collapsedGames: new Map(),
        newGameName: '',
        newGameError: '',
        players: [],
        selectedPlayer: '',
      }
    },
    methods: {
      toggleCollapse(gameid) {
        this.collapsedGames.set(gameid, !this.collapsedGames.get(gameid));
      },
      
      async prepareAdminPage() {
        try {
          const result = await fetchBingoGames();
          this.games = result.data;
          this.upcomingGames = [];
          this.runningGames = [];
          for (const game of this.games) {
            if (game.status === 1) {
              this.runningGames.push(game);
              this.collapsedGames.set(game.gameid, true);
              const eventsResult = await fetchBingoEvents(game.gameid);
              this.events.set(game.gameid, eventsResult.data);
            } else if (game.status === 0) {
              this.upcomingGames.push(game);
              this.collapsedGames.set(game.gameid, true);
              const eventsResult = await fetchBingoEvents(game.gameid);
              this.events.set(game.gameid, eventsResult.data);
            }
          }
        } catch (err) {
        }
      },

      async createGame() {
        const name = this.newGameName.trim();
        if (!name) {
          this.newGameError = 'Please enter a game name';
          return;
        }

        console.log('Creating game:', name);
        try {
          const result = await createBingoGame(name);
          console.log('Game created successfully:', result.data);
          await this.prepareAdminPage();
        } catch (err) {
          if (err.response) {
            if (err.response.status === 403) {
              this.newGameError = 'Insufficient permissions to create a game';
            } else if (err.response.status === 409) {
              this.newGameError = 'Game with this name already exists';
            }
          } else {
            this.newGameError = 'Failed to create game. Please try again.';
          }
          this.newGameName = '';
          return;
        }

        this.newGameError = '';
        this.newGameName = '';
      },

      async stopGame(gameid) {
        try {
          await updateGameStatus(gameid, 2);
          await this.prepareAdminPage();
        } catch (err) {
          if (err.response) {
            if (err.response.status === 403) {
              console.error('Insufficient permissions to stop the game');
            } else if (err.response.status === 409) {
              console.error('Game does not exist or is already stopped');
            }
          }
        }
      },

      async startGame(gameid) {
        try {
          await updateGameStatus(gameid, 1);
          await this.prepareAdminPage();
        } catch (err) {
          if (err.response) {
            if (err.response.status === 403) {
              console.error('Insufficient permissions to start the game');
            } else if (err.response.status === 409) {
              console.error('Game does not exist or is already running');
            }
          }
        }
      },

      async uploadEvents(gameid) {
        const input = this.$refs['eventUpload' + gameid][0];
        if (!input || !input.files || input.files.length !== 1) return;

        const file = input.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('files', file);
        formData.append('gameid', gameid);

        try {
          await createBingoEvents(formData);
          await this.prepareAdminPage();
          await this.toggleCollapse(gameid);
        } catch (err) {
          if (err.response) {
            if (err.response.status === 403) {
              //console.error('Insufficient permissions to upload events');
            } else if (err.response.status === 409) {
              //console.error('Game does not exist or is already running');
            }
          }
        }
      },

      async loadPlayers() {
        try {
          const result = await fetchUsers();
          this.players = result.data;
        } catch (err) {
        }
      },

      async uploadBingoBoard(gameid) {
        const input = this.$refs['bingoBoardUpload' + gameid][0];
        if (!input || !input.files || input.files.length !== 1) return;

        const file = input.files[0];
        if (!file) return;

        if (!this.selectedPlayer) {
          //TODO maybe show error to select player in the select box
          return;
        }

        const formData = new FormData();
        formData.append('files', file);
        formData.append('gameid', gameid);
        formData.append('playerid', this.selectedPlayer);

        console.log('Uploading bingo board: ', formData);

        try {
          await createBingoBoard(formData);
          this.selectedPlayer = '';
          await this.prepareAdminPage();
          await this.toggleCollapse(gameid);
        } catch (err) {
          if (err.response) {
            if (err.response.status === 403) {
              //console.error('Insufficient permissions to upload bingo board');
            } else if (err.response.status === 409) {
              //console.error('Game does not exist or is already running');
            }
          }
        }
      }
    },
    mounted() {
      this.prepareAdminPage();
      this.loadPlayers();
    }
  }
</script>

<style scoped>
  .input-error {
    border: 1px solid #e74c3c;
  }

  .input-error::placeholder {
    color: #e74c3c;
  }
</style>
