<template>
    <v-container class="game">
        <v-text-field v-model="search" label="Search Event" prepend-inner-icon="fa-solid fa-search"/>
        <v-row>
            <v-col cols="3/5">Event Name</v-col>
            <v-col cols="1/5">Happened</v-col>
            <v-col cols="1/5"></v-col>
        </v-row>
        <v-container class="overflow-y-auto" height="400px">
            <v-row v-for="event in filteredEvents" :key="event.id" :class="(event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done')">
                <v-col cols="3/5">{{ event.event }}</v-col>
                <v-col v-if="event.amountbased" cols="1/5">({{ event.amounthappened }} / {{ event.amountneeded }})</v-col>
                <v-col v-else cols="1/5"></v-col>
                <v-col v-if="event.amountbased" align="right" :hidden="game.status !== 1" cols="1/5">
                    <v-btn @click="updateEventInList(event.id, true)" :disabled="!event.amountbased && event.amounthappened >= event.amountneeded">
                        <v-icon icon="fa-solid fa-plus"></v-icon>
                    </v-btn>
                    <v-btn @click="updateEventInList(event.id, false)" :disabled="event.amounthappened <= -1">
                        <v-icon icon="fa-solid fa-minus"></v-icon>
                    </v-btn>
                </v-col>
                <v-col v-else align="right" :hidden="game.status !== 1" cols="1/5">
                    <v-btn @click="updateEventInList(event.id, true)" :disabled="!event.amountbased && event.amounthappened >= event.amountneeded">
                        <v-icon icon="fa-solid fa-circle-plus"></v-icon>
                    </v-btn>
                    <v-btn @click="updateEventInList(event.id, false)" :disabled="event.amounthappened <= -1">
                        <v-icon icon="fa-solid fa-circle-minus"></v-icon>
                    </v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
</template>

<script>
    import { fetchBingoEvents, fetchBingoGames, updateBingoEvent } from '@/services/bingoService.js';
    import Fuse from 'fuse.js';

    export default {
        name: 'EventList',
        props: {
            gameId: String
        },
        data() {
            return {
				events: [],
                game: null,
                fuse: null,
                search: '',
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

            async fetchEvents() {
                try {
                    const result = await fetchBingoEvents(this.gameId);
                    this.events = result.data;
                    console.log(this.events);
                    this.fuse = new Fuse(this.events, {
                        keys: ['event'],
                        threshold: 0.3
                    });
                } catch (err) {
                    this.events = [];
                }
            },

            async updateEventInList(id, increase) {
                try {
                    await updateBingoEvent(id, increase);
                    this.fetchEvents();
                }catch (err) {
                }
            },
        },
        async mounted() {
            await this.fetchGame();
            this.fetchEvents();
        },
        computed: {
            filteredEvents() {
                if (!this.search) return this.events;
                return this.fuse.search(this.search).map(result => result.item);
            }
        }
    }
</script>

<style scoped>
    v-btn {
		width: 24px;
		height: 24px;
		border-radius: 50%;
	}
</style>
