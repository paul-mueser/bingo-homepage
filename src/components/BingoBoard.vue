<template>
    <div class="container">
      	<h1>{{ user }}</h1>
		<div v-for="game in games" :key="game.gameid">
			<h2>{{ game.name }} ({{ points.get(game.gameid) }} Punkte)</h2>
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
</template>
  
<script>
	import { fetchBingoBoard } from '../services/bingoService.js';

	export default {
		name: 'EventList',
		props: {
			user: String
		},
		data() {
			return {
				events: new Map(),
				points: new Map(),
				games: []
			}
		},
		methods: {
			async fetchBoard(gameid) {
				try {
					const result = await fetchBingoBoard(this.user, gameid);
					const data = result.data;
					for (let i = 0; i < 5; i++) {
						this.events.get(gameid).push(data.slice(i * 5, i * 5 + 5));
					}
				} catch (err) {
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

			async prepareBingoBoards() {
				try {
					const result = await fetchBingoGames();
					this.games = result.data;
					for (const game of this.games) {
						this.events.set(game.gameid, []);
						this.points.set(game.gameid, 0);
						this.fetchBoard(game.gameid);
					}
				} catch (err) {
					console.error('Error fetching bingo games:', err);
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
</style>
  