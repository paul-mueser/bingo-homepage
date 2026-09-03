<template>
    <v-container class="game">
        <v-sheet class="pa-4" elevation="2" style="margin-bottom: 1em;">
            <v-form>
                <v-text-field v-model="eventName" label="Event Name" required/>
                <v-checkbox v-model="amountBased" label="Amount Based?"/>
                <v-btn color="primary" @click="handleEventSubmit" :disabled="eventName===''">Submit</v-btn>
            </v-form>
        </v-sheet>
        <v-text-field v-model="search" label="Search Event" prepend-inner-icon="fa-solid fa-search"/>
        <v-row :gap="mobile ? 0 : 24">
            <v-col :cols="colVals.eventTextLarge">Event Name</v-col>
            <v-col :cols="colVals.small">Amountbased?</v-col>
        </v-row>
        <v-container class="overflow-y-auto" height="400px" style="padding: 0">
            <v-row v-for="event in filteredEvents" :key="event.id" :class="'eventRow'" style="margin-top: 5px; margin-bottom: 5px; min-height: 32px;" align="center" :gap="mobile ? 0 : 24">
                <v-col :cols="colVals.eventTextLarge">{{ event.event }}</v-col>
                <v-col v-if="event.amountbased" :cols="colVals.small"><font-awesome-icon icon="fa-solid fa-x"/></v-col>
                <v-col v-else :cols="colVals.small"><font-awesome-icon icon="fa-solid fa-check"/></v-col>
            </v-row>
        </v-container>
    </v-container>
</template>

<script setup>
    import { useDisplay } from 'vuetify';
    const { mobile } = useDisplay();
    const colVals =  { eventTextLarge: '8/10', eventTextSmall: '5/10', medium: '2/10', small: '1/10', buttons: '2/10' };
</script>

<script>
    import { fetchEventSubmissions, addEventSubmission } from '@/services/bingoService.js';
    import Fuse from 'fuse.js';

    export default {
        name: 'EventSubmissionForm',
        data() {
            return {
				events: [],
                fuse: null,
                search: '',
                eventName: '',
                amountBased: false
            }
        },
        methods: {
            async fetchEvents() {
                try {
                    const result = await fetchEventSubmissions();
                    this.events = result.data;
                    this.fuse = new Fuse(this.events, {
                        keys: ['event'],
                        threshold: 0.3,
                        ignoreLocation: true
                    });
                } catch (err) {
                    this.events = [];
                }
            },

            async handleEventSubmit() {
                if (!this.eventName) {
                    alert('Event name is required');
                    return;
                }
                try {
                    await addEventSubmission(this.eventName, this.amountBased);
                    await this.fetchEvents();
                } catch (err) {
                }
                this.eventName = '';
                this.amountBased = false;
            },
        },
        async mounted() {
            this.fetchEvents();
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

<style scoped>
    .eventRow {
        padding-left: 0.5em;
        padding-right: 0.5em;
        border: 1px solid var(--text-color);
        border-radius: 4px;
    }
</style>
