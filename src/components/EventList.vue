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
                            <button @click="updateEventInList(event.id, true)" :disabled="!event.amountbased && event.amounthappened >= event.amountneeded">+</button>
                            <button @click="updateEventInList(event.id, false)" :disabled="event.amounthappened <= -1">-</button>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="event in events.get(game.gameid)" :key="event.id">
                        <td>{{ event.event }}</td>
                        <td align="center">{{ !event.amountbased ? (event.amounthappened >= event.amountneeded) : event.amounthappened + '/' + event.amountneeded }}</td>
                        <td :class="event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done'"> </td>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="event in events.get(game.gameid)" :key="event.id">
                        <td>{{ event.event }}</td>
                        <td align="center">{{ !event.amountbased ? (event.amounthappened >= event.amountneeded) : event.amounthappened + '/' + event.amountneeded }}</td>
                        <td :class="event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done'"> </td>
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

            async updateEventInList(id, increase) {
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
    button {
		width: 24px;
		height: 24px;
		border-radius: 50%;
	}
</style>
  
