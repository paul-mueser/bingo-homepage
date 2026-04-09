<template>
	<v-container class="game" :style="boardStyle">
		<v-row v-for="eventRow in events" v-if="game" :key="eventRow" density="compact" gap="0">
			<v-col v-for="event in eventRow" :key="event.id" :class="'bingo-field ' + (event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done') + ((!event.amountbased && game.status === 1) ? ' clickableField' : '')" @click="game.status === 1 && !event.amountbased && handleClick(game.gameid, event)" cols="1/5">
				<div class="wrapper">
					<div class="cell-text">
						{{ event.event }}
					</div>
					<v-divider thickness="3" class="black" opacity="1" gradient></v-divider>
					<div v-if="event.amountbased" class="bottom content">
						<v-btn @click="updateEventOnBoard(game.gameid, event.id, true)" :disabled="!event.amountbased && event.amounthappened >= event.amountneeded" :style="(game.status !== 1) ? 'display:none' : ''" icon="fa-solid fa-circle-plus" density="compact"></v-btn>
						<span>({{ event.amounthappened }} / {{ event.amountneeded }})</span>
						<v-btn @click="updateEventOnBoard(game.gameid, event.id, false)" :disabled="event.amounthappened <= -1" :style="(game.status !== 1) ? 'display:none' : ''" icon="fa-solid fa-circle-minus" density="compact"></v-btn>
					</div>
					<v-divider v-if="event.amountbased" thickness="3" class="black" opacity="1" gradient></v-divider>
					<div class="bottom content">
						<span>{{ event.points }} Points</span>
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
			_onEventUpdated(e) {
				this.fetchBoard();
			},

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
					window.dispatchEvent(new CustomEvent('event-updated'));
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
						window.dispatchEvent(new CustomEvent('event-updated'));
					} catch (err) {
					}
				}
			},
		},
		async mounted() {
			await this.fetchGame();
			this.fetchBoard();
			window.addEventListener('event-updated', this._onEventUpdated);
		},
		beforeUnmount() {
			window.removeEventListener('event-updated', this._onEventUpdated);
		},
		computed: {
			boardScale() {
				if (!this.$vuetify.display.mobile) return 1;

				const baseSize = 830;
				const screenWidth = window.innerWidth;

				return Math.min(1, screenWidth / baseSize);
			},

			boardStyle() {
				return {
					width: '762px',
					height: '762px',
					transform: `scale(${this.boardScale})`,
					transformOrigin: 'top left'
				}
			}
		}
	}
</script>

<style scoped>
	.board-content {
		width: 762px;
		height: 762px;
	}

	.bingo-field {
		box-sizing: border-box;
		width: 150px;
		height: 150px;
		border: 1px solid var(--text-color);
		border-radius: 4px;
		vertical-align: middle;
	}

	.wrapper {
		display: flex;
  		flex-direction: column;
		height: 146px;
		aspect-ratio: 1;
	}

	.cell-text {
		overflow: auto;
		text-align: left;
		max-height: 146px;
		max-width: 150px;
		padding-left: 0.5em;
		padding-right: 0.5em;
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
</style>
  