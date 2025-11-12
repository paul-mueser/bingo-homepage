<template>
    <div class="container">
        <h1>Events</h1>
		<h1>Running Games</h1>
        <div v-for="game in runningGames" :key="game.gameid" class="game">
            <h2 @click="toggleCollapse(game.gameid)" class="game-title"><span class="caret">{{ collapsedGames.get(game.gameid) ? '▸' : '▾' }}</span> {{ game.name }}</h2>
            <div v-show="!collapsedGames.get(game.gameid)">
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
        <h1>Upcoming Games</h1>
        <div v-for="game in upcomingGames" :key="game.gameid" class="game">
            <h2 @click="toggleCollapse(game.gameid)" class="game-title"><span class="caret">{{ collapsedGames.get(game.gameid) ? '▸' : '▾' }}</span> {{ game.name }}</h2>
            <div v-show="!collapsedGames.get(game.gameid)">
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
        <h1>Finished Games</h1>
        <div v-for="game in finishedGames" :key="game.gameid" class="game">
            <h2 @click="toggleCollapse(game.gameid)" class="game-title"><span class="caret">{{ collapsedGames.get(game.gameid) ? '▸' : '▾' }}</span> {{ game.name }}</h2>
            <div v-show="!collapsedGames.get(game.gameid)">
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
    </div>
</template>

<script>
    import { fetchBingoEvents, fetchBingoGames, updateBingoEvent } from '../services/bingoService.js';

    export default {
        name: 'EventList',
        data() {
            return {
				runningGames: [],
				upcomingGames: [],
				finishedGames: [],
                games: [],
                events: new Map(),
                collapsedGames: new Map()
            }
        },
        methods: {
            toggleCollapse(gameid) {
				this.collapsedGames.set(gameid, !this.collapsedGames.get(gameid));
			},
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
                    for (const game of this.games) {
                        if (game.status === 1) {
							this.runningGames.push(game);
							this.collapsedGames.set(game.gameid, false);
						} else if (game.status === 0) {
							this.upcomingGames.push(game);
							this.collapsedGames.set(game.gameid, true);
						} else if (game.status === 2) {
							this.finishedGames.push(game);
                            this.collapsedGames.set(game.gameid, true);
						}
                    }
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
</style>
  
