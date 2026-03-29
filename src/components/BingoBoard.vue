<template>
	<v-container class="game">
		<v-row v-for="eventRow in events" v-if="game" :key="eventRow">
			<v-col v-for="event in eventRow" :key="event.id" :class="'bingo-field ' + (event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done') + ((!event.amountbased && game.status === 1) ? ' clickableField' : '')" @click="!event.amountbased && handleClick(game.gameid, event)" cols="1/5">
				<div class="wrapper">
					<div class="cell-text">
						{{ event.event }}
					</div>
					<div v-if="event.amountbased" class="bottom content">
						<button @click="updateEventOnBoard(game.gameid, event.id, true)" :disabled="!event.amountbased && event.amounthappened >= event.amountneeded" :hidden="game.status !== 1">+</button>
						<span>({{ event.amounthappened }} / {{ event.amountneeded }})</span>
						<button @click="updateEventOnBoard(game.gameid, event.id, false)" :disabled="event.amounthappened <= -1" :hidden="game.status !== 1">-</button>
					</div>
				</div>
			</v-col>
		</v-row>
	</v-container>
</template>

<script>
	import { fetchBingoBoard, fetchBingoGames, updateBingoEvent } from '@/services/bingoService.js';

	export default {
		name: 'BingoBoard',
		props: {
			gameId: String,
			userid: String
		},
		data() {
			return {
				events: [],
				game: null
			}
		},
		methods: {
			async fetchGame() {
				try {
					const result = await fetchBingoGames();
					const data = result.data;
					this.game = data.find(g => g.gameid === parseInt(this.gameId));
				} catch (err) {
					this.game = null;
				}
			},

			async fetchBoard() {
				try {
					const result = await fetchBingoBoard(this.userid, this.gameId);
					const data = result.data;
					if (!data || data.length === 0) {
						throw new Error('No bingo board found');
					}
					this.events = [];
					for (let i = 0; i < 5; i++) {
						this.events.push(data.slice(i * 5, i * 5 + 5));
					}
				} catch (err) {
					this.events = [];
					return;
				}
			},

			async updateEventOnBoard(gameid, eventid, increase) {
                try {
                    await updateBingoEvent(eventid, increase);
					this.fetchBoard();
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
						this.fetchBoard();
					} catch (err) {
					}
				}
			},
		},
		async mounted() {
			await this.fetchGame();
			this.fetchBoard();
		}
	}
</script>

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
  