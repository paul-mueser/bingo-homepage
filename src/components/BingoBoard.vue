<template>
    <div class="container">
      	<h1>{{ user }}</h1>
		<h1>Running Games</h1>
		<div v-for="game in runningGames" :key="game.gameid" class="game">
			<h2 @click="toggleCollapse(game.gameid)" class="game-title"><span class="caret">{{ collapsedGames.get(game.gameid) ? '▸' : '▾' }}</span> {{ game.name }} ({{ points.get(game.gameid) }} Punkte)</h2>
			<div v-show="!collapsedGames.get(game.gameid)">
				<table>
					<tbody>
						<tr v-for="eventRow in events.get(game.gameid)" :key="eventRow">
							<td v-for="event in eventRow" :key="event.id" :class="'bingo-field ' + (event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done') + (!event.amountbased ? ' clickableField' : '')" @click="!event.amountbased && handleClick(game.gameid, event)">
								<div class="wrapper">
									<div class="cell-text">
										{{ event.event }}
									</div>
									<div v-if="event.amountbased" class="bottom content">
										<button @click="updateEventOnBoard(game.gameid, event.id, true)" :disabled="!event.amountbased && event.amounthappened >= event.amountneeded">+</button>
										<span>({{ event.amounthappened }} / {{ event.amountneeded }})</span>
										<button @click="updateEventOnBoard(game.gameid, event.id, false)" :disabled="event.amounthappened <= -1">-</button>
									</div>
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
						<tr v-for="eventRow in events.get(game.gameid)" :key="eventRow">
							<td v-for="event in eventRow" :key="event.id" :class="'bingo-field ' + (event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done')">
								<div class="wrapper">
									<div class="cell-text">
										{{ event.event }}
									</div>
									<div v-if="event.amountbased" class="bottom content">
										<span>({{ event.amounthappened }} / {{ event.amountneeded }})</span>
									</div>
								</div>
							</td>
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
						<tr v-for="eventRow in events.get(game.gameid)" :key="eventRow">
							<td v-for="event in eventRow" :key="event.id" :class="'bingo-field ' + (event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done')">
								<div class="wrapper">
									<div class="cell-text">
										{{ event.event }}
									</div>
									<div v-if="event.amountbased" class="bottom content">
										<span>({{ event.amounthappened }} / {{ event.amountneeded }})</span>
									</div>
								</div>
							</td>
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

			async updateEventOnBoard(gameid, eventid, increase) {
                try {
                    await updateBingoEvent(eventid, increase);
					this.fetchBoard(gameid);
                }catch (err) {
                }
            },

			async handleClick(gameid, event) {
				if (event.amounthappened < 1) {
					await this.updateEventOnBoard(gameid, event.id, true);
				} else {
					try {
						await updateBingoEvent(event.id, false);
						await updateBingoEvent(event.id, false);
						this.fetchBoard(gameid);
					} catch (err) {
					}
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
			console.log(this.user);
			this.prepareBingoBoards();
		}
	}
</script>
  
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
	table {
		height: 762px;
		width: 762px;
	}

	.bingo-field {
		box-sizing: border-box;
		width: 150px;
		height: 150px;
		border: 1px solid black;
		vertical-align: middle;
	}

	.wrapper {
		display: flex;
  		flex-direction: column;
		height: 146px;
	}

	.cell-text {
		overflow: auto;
		text-align: left;
		max-height: 146px;
		max-width: 150px;
	}

	.bottom {
		margin-top: auto;
	}

	.clickableField {
		cursor: pointer;
		position: relative;
		user-select: none;
	}

	.clickableField::before {
		content: '';
		position: absolute;
		inset: 0;
		pointer-events: none;
		mix-blend-mode: lighten;
		transition: background-color .15s ease, opacity .15s ease;
	}

	.clickableField:hover::before {
		background-color: rgba(255, 255, 255, 0.3);
	}

	button {
		width: 24px;
		height: 24px;
		border-radius: 50%;
	}
</style>
  