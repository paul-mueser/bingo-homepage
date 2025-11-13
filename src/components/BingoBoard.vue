<template>
    <div class="container">
      	<h1>{{ user }}</h1>
		<h1>Running Games</h1>
		<div v-for="game in runningGames" :key="game.gameid" class="game">
			<h2 @click="toggleCollapse(game.gameid)" class="game-title"><span class="caret">{{ collapsedGames.get(game.gameid) ? '▸' : '▾' }}</span> {{ game.name }} ({{ points.get(game.gameid) }} Punkte)</h2>
			<div v-show="!collapsedGames.get(game.gameid)">
				<table>
					<tbody>
						<tr v-for="event in events.get(game.gameid)" :key="event.id">
						<td :class="event[0].amounthappened < event[0].amountneeded ? (event[0].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">
							{{ event[0].event }}
							<div class="content">
								<button @click="updateEvent(game.gameid, event[0].id, true)" :disabled="!event[0].amountbased && event[0].amounthappened >= event[0].amountneeded">Add 1</button>
                            	<button @click="updateEvent(game.gameid, event[0].id, false)" :disabled="event[0].amounthappened <= -1">Sub 1</button>
							</div>
						</td>
						<td :class="event[1].amounthappened < event[1].amountneeded ? (event[1].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">
							{{ event[1].event }}
							<div class="content">
								<button @click="updateEvent(game.gameid, event[1].id, true)" :disabled="!event[1].amountbased && event[1].amounthappened >= event[1].amountneeded">Add 1</button>
								<button @click="updateEvent(game.gameid, event[1].id, false)" :disabled="event[1].amounthappened <= -1">Sub 1</button>
							</div>
						</td>
						<td :class="event[2].amounthappened < event[2].amountneeded ? (event[2].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">
							{{ event[2].event }}
							<div class="content">
								<button @click="updateEvent(game.gameid, event[2].id, true)" :disabled="!event[2].amountbased && event[2].amounthappened >= event[2].amountneeded">Add 1</button>
								<button @click="updateEvent(game.gameid, event[2].id, false)" :disabled="event[2].amounthappened <= -1">Sub 1</button>
							</div>
						</td>
						<td :class="event[3].amounthappened < event[3].amountneeded ? (event[3].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">
							{{ event[3].event }}
							<div class="content">
								<button @click="updateEvent(game.gameid, event[3].id, true)" :disabled="!event[3].amountbased && event[3].amounthappened >= event[3].amountneeded">Add 1</button>
								<button @click="updateEvent(game.gameid, event[3].id, false)" :disabled="event[3].amounthappened <= -1">Sub 1</button>
							</div>
						</td>
						<td :class="event[4].amounthappened < event[4].amountneeded ? (event[4].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">
							{{ event[4].event }}
							<div class="content">
								<button @click="updateEvent(game.gameid, event[4].id, true)" :disabled="!event[4].amountbased && event[4].amounthappened >= event[4].amountneeded">Add 1</button>
								<button @click="updateEvent(game.gameid, event[4].id, false)" :disabled="event[4].amounthappened <= -1">Sub 1</button>
							</div>
						</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<h1>Upcomming Games</h1>
		<div v-for="game in upcomingGames" :key="game.gameid" class="game">
			<h2 @click="toggleCollapse(game.gameid)" class="game-title"><span class="caret">{{ collapsedGames.get(game.gameid) ? '▸' : '▾' }}</span> {{ game.name }} ({{ points.get(game.gameid) }} Punkte)</h2>
			<div v-show="!collapsedGames.get(game.gameid)">
				<table>
					<tbody>
						<tr v-for="event in events.get(game.gameid)" :key="event.id">
						<td :class="event[0].amounthappened < event[0].amountneeded ? (event[0].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">{{ event[0].event }}</td>
						<td :class="event[1].amounthappened < event[1].amountneeded ? (event[1].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">{{ event[1].event }}</td>
						<td :class="event[2].amounthappened < event[2].amountneeded ? (event[2].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">{{ event[2].event }}</td>
						<td :class="event[3].amounthappened < event[3].amountneeded ? (event[3].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">{{ event[3].event }}</td>
						<td :class="event[4].amounthappened < event[4].amountneeded ? (event[4].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">{{ event[4].event }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<h1>Finished Games</h1>
		<div v-for="game in finishedGames" :key="game.gameid" class="game">
			<h2 @click="toggleCollapse(game.gameid)" class="game-title"><span class="caret">{{ collapsedGames.get(game.gameid) ? '▸' : '▾' }}</span> {{ game.name }} ({{ points.get(game.gameid) }} Punkte)</h2>
			<div v-show="!collapsedGames.get(game.gameid)">
				<table>
					<tbody>
						<tr v-for="event in events.get(game.gameid)" :key="event.id">
						<td :class="event[0].amounthappened < event[0].amountneeded ? (event[0].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">{{ event[0].event }}</td>
						<td :class="event[1].amounthappened < event[1].amountneeded ? (event[1].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">{{ event[1].event }}</td>
						<td :class="event[2].amounthappened < event[2].amountneeded ? (event[2].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">{{ event[2].event }}</td>
						<td :class="event[3].amounthappened < event[3].amountneeded ? (event[3].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">{{ event[3].event }}</td>
						<td :class="event[4].amounthappened < event[4].amountneeded ? (event[4].amounthappened < 0 ? 'impossible' : 'not-done') : 'done'">{{ event[4].event }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
    </div>
</template>
  
<script>
	import { fetchBingoBoard, fetchBingoGames, updateBingoEvent } from '../services/bingoService.js';

	export default {
		name: 'EventList',
		props: {
			user: String
		},
		data() {
			return {
				events: new Map(),
				points: new Map(),
				runningGames: [],
				upcomingGames: [],
				finishedGames: [],
				collapsedGames: new Map()
			}
		},
		methods: {
			toggleCollapse(gameid) {
				this.collapsedGames.set(gameid, !this.collapsedGames.get(gameid));
			},

			async fetchBoard(gameid) {
				try {
					const result = await fetchBingoBoard(this.user, gameid);
					const data = result.data;
					if (!data || data.length === 0) {
						throw new Error('No bingo board found');
					}
					this.events.set(gameid, []);
					this.points.set(gameid, 0);
					for (let i = 0; i < 5; i++) {
						this.events.get(gameid).push(data.slice(i * 5, i * 5 + 5));
					}
				} catch (err) {
					this.runningGames = this.runningGames.filter(game => game.gameid !== gameid);
					this.upcomingGames = this.upcomingGames.filter(game => game.gameid !== gameid);
					this.finishedGames = this.finishedGames.filter(game => game.gameid !== gameid);
					return;
				}

				let bingoCount = 0;

				function isBingo(line) {
					return line.every(event => event.amounthappened >= event.amountneeded);
				}

				for (let i = 0; i < 5; i++) {
					if (isBingo(this.events.get(gameid)[i])) {
						bingoCount++;
					}

					let column = [];

					for (let j = 0; j < 5; j++) {
						if (this.events.get(gameid)[i][j].amounthappened >= this.events.get(gameid)[i][j].amountneeded) {
							this.points.set(gameid, this.points.get(gameid) + this.events.get(gameid)[i][j].points);
						}
						column.push(this.events.get(gameid)[j][i]);
					}

					if (isBingo(column)) {
						bingoCount++;
					}
				}

				let diagonal1 = [];
				let diagonal2 = [];
				for (let i = 0; i < 5; i++) {
					diagonal1.push(this.events.get(gameid)[i][i]);
					diagonal2.push(this.events.get(gameid)[i][4 - i]);
				}

				if (isBingo(diagonal1)) {
					bingoCount++;
				}

				if (isBingo(diagonal2)) {
					bingoCount++;
				}

				this.points.set(gameid, this.points.get(gameid) + bingoCount * 100);
			},

			async updateEvent(gameid, eventid, increase) {
                try {
                    await updateBingoEvent(eventid, increase);
					this.fetchBoard(gameid);
                }catch (err) {
                }
            },

			async prepareBingoBoards() {
				try {
					const result = await fetchBingoGames();
					this.games = result.data;
					for (const game of result.data) {
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
						this.events.set(game.gameid, []);
						this.points.set(game.gameid, 0);
						this.fetchBoard(game.gameid);
					}
				} catch (err) {
				}
			}
		},
		mounted() {
			this.prepareBingoBoards();
		}
	}
</script>
  
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
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

	.content {
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    white-space: nowrap;
    }
</style>
  