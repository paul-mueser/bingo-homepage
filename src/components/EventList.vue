<template>
    <v-container class="game">
        <v-text-field v-model="search" label="Search Event" prepend-inner-icon="fa-solid fa-search"/>
        <v-row :gap="mobile ? 0 : 24">
            <v-col cols="4/6">Event Name</v-col>
            <v-col cols="1/6">Points</v-col>
            <v-col cols="1/6"></v-col>
        </v-row>
        <v-container class="overflow-y-auto" height="400px">
            <v-row v-for="event in filteredEvents" :key="event.id" :class="(event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done')" style="margin-top: 5px; margin-bottom: 5px; min-height: 32px;" align="center" :gap="mobile ? 0 : 24">
                <v-col :cols="event.amountbased ? colVals.eventTextSmall : colVals.eventTextLarge">{{ event.event }}</v-col>
                <v-col v-if="event.amountbased" :cols="colVals.medium">({{ event.amounthappened }} / {{ event.amountneeded }})</v-col>
                <v-col v-else :cols="colVals.small"></v-col>
                <v-col :cols="colVals.small">{{ event.points }}P</v-col>
                <v-col v-if="event.amountbased" :hidden="game.status !== 1" :cols="colVals.buttons" style="margin-top: 5px; margin-bottom: 5px;">
                    <v-btn @click="updateEventInList(event.id, true)" :disabled="!event.amountbased && event.amounthappened >= event.amountneeded" density="compact" icon="fa-solid fa-circle-plus"></v-btn>
                    <v-btn @click="updateEventInList(event.id, false)" :disabled="event.amounthappened <= 0" density="compact" icon="fa-solid fa-circle-minus"></v-btn>
                </v-col>
                <v-col v-else :hidden="game.status !== 1" :cols="colVals.buttons">
                    <v-switch :key="event.id" :model-value="event.amounthappened === event.amountneeded" @update:modelValue="val => updateEventInList(event.id, val)" inset></v-switch>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
</template>

<script setup>
    import { useDisplay } from 'vuetify';
    const { mobile } = useDisplay();
    const colVals =  { eventTextLarge: '6/10', eventTextSmall: '5/10', medium: '2/10', small: '1/10', buttons: '2/10' };
</script>

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
            _onEventUpdated(e) {
                this.fetchEvents();
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

            async fetchEvents() {
                try {
                    const result = await fetchBingoEvents(this.gameId);
                    this.events = result.data;
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
                    window.dispatchEvent(new CustomEvent('event-updated'));
                }catch (err) {
                }
            },
        },
        async mounted() {
            await this.fetchGame();
            this.fetchEvents();
            window.addEventListener('event-updated', this._onEventUpdated);
        },
        beforeUnmount() {
            window.removeEventListener('event-updated', this._onEventUpdated);
        },
        computed: {
            filteredEvents() {
                if (!this.search) return this.events;
                if (!this.fuse) return this.events;
                return this.fuse.search(this.search).map(result => result.item);
            }
        }
    }
</script>
