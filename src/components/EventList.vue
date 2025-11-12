<template>
    <div class="container">
        <h1>Events</h1>
        <div v-for="game in games" :key="game.gameid">
            <h2>{{ game.name }}</h2>
            <table>
                <thead>
                    <tr>
                    <th>Event Name</th>
                    <th>Happened/Needed</th>
                    <th>Done?</th>
                    <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="event in events.get(game.gameid)" :key="event.id">
                    <td>{{ event.event }}</td>
                    <td align="center">{{ !event.amountbased ? (event.amounthappened >= event.amountneeded) : event.amounthappened + '/' + event.amountneeded }}</td>
                    <td :class="event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done'"> </td>
                    <td class="content">
                        <button @click="updateEvent(event.id, true)" :disabled="!event.amountbased && event.amounthappened >= event.amountneeded">Add 1</button>
                        <button @click="updateEvent(event.id, false)" :disabled="event.amounthappened <= -1">Sub 1</button>
                    </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import { fetchBingoEvents, fetchBingoGames, updateBingoEvent } from '../services/bingoService.js';

    export default {
        name: 'EventList',
        data() {
            return {
                games: [],
                events: new Map()
            }
        },
        methods: {
            async fetchAllEvents() {
                for (const game of this.games) {
                    try {
                        const result = await fetchBingoEvents(game.gameid);
                        this.events.set(game.gameid, result.data);
                    } catch (err) {
                    }
                }
            },

            async updateEvent(id, increase) {
                try {
                    await updateBingoEvent(id, increase);
                    this.fetchAllEvents();
                }catch (err) {
                }
            },

            async prepareBingoEvents() {
                try {
                    const result = await fetchBingoGames();
                    this.games = result.data;
                    this.fetchAllEvents();
                } catch (err) {
                }
            }
        },
        mounted() {
            this.prepareBingoEvents();
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .content {
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    white-space: nowrap;
    }
    
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
  
