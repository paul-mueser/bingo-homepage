<template>
    <div class="container">
      	<h1>{{ user }} ({{ points }} Punkte)</h1>
		<table>
            <tbody>
                <tr v-for="event in events" :key="event.id">
                <td :class="event[0].amounthappened < event[0].amountneeded ? 'not-done' : 'done'">{{ event[0].event }}</td>
                <td :class="event[1].amounthappened < event[1].amountneeded ? 'not-done' : 'done'">{{ event[1].event }}</td>
                <td :class="event[2].amounthappened < event[2].amountneeded ? 'not-done' : 'done'">{{ event[2].event }}</td>
                <td :class="event[3].amounthappened < event[3].amountneeded ? 'not-done' : 'done'">{{ event[3].event }}</td>
                <td :class="event[4].amounthappened < event[4].amountneeded ? 'not-done' : 'done'">{{ event[4].event }}</td>
                </tr>
            </tbody>
        </table>
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
				events: [],
				points: 0
			}
		},
		methods: {
			async fetchBoard() {
				try {
					const result = await fetchBingoBoard(this.user);
					const data = result.data;
					for (let i = 0; i < 5; i++) {
						this.events.push(data.slice(i * 5, i * 5 + 5));
					}
					console.log(this.events);
				} catch (err) {
					console.error('Error:', err);
				}

				let bingoCount = 0;

				function isBingo(line) {
					return line.every(event => event.amounthappened >= event.amountneeded);
				}

				for (let i = 0; i < 5; i++) {
					if (isBingo(this.events[i])) {
						bingoCount++;
					}

					let column = [];

					for (let j = 0; j < 5; j++) {
						if (this.events[i][j].amounthappened >= this.events[i][j].amountneeded) {
							this.points += this.events[i][j].points;
						}
						column.push(this.events[j][i]);
					}

					if (isBingo(column)) {
						bingoCount++;
					}
				}

				let diagonal1 = [];
				let diagonal2 = [];
				for (let i = 0; i < 5; i++) {
					diagonal1.push(this.events[i][i]);
					diagonal2.push(this.events[i][4 - i]);
				}

				if (isBingo(diagonal1)) {
					bingoCount++;
				}

				if (isBingo(diagonal2)) {
					bingoCount++;
				}

				this.points += bingoCount * 20; //TODO update points for bingo
			},
		},
		mounted() {
			this.fetchBoard();
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
</style>
  