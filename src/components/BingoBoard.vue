<template>
    <div>
      	<h1>{{ user }}</h1>
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
			events: []
			}
		},
		methods: {
			async fetchBoard() {
				try {
					const result = await fetchBingoBoard(this.user);
					const data = result.data;
					for (let i = 0; i < 5; i++) {
						events.push(data.slice(i * 5, i * 5 + 5));
					}
					console.log(events);
				} catch (err) {
					console.error('Error:', err);
				}
			},
		},
		mounted() {
			this.fetchBoard();
		}
	}
</script>
  
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
	h3 {
	margin: 40px 0 0;
	}
	ul {
	list-style-type: none;
	padding: 0;
	}
	li {
	display: inline-block;
	margin: 0 10px;
	}
	a {
	color: #42b983;
	}

	.not-done {
	background-color: red;
	}

	.done {
		background-color: green;
	}
</style>
  