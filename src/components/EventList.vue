<template>
    <div class="container">
        <h1>Events</h1>
        <table>
            <thead>
                <tr>
                <th>Event Name</th>
                <th>Happened/Needed</th>
                <th>Done?</th>
                <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="event in events" :key="event.id">
                <td>{{ event.event }}</td>
                <td>{{ !event.amountbased ? (event.amounthappened >= event.amountneeded) : event.amounthappened + '/' + event.amountneeded }}</td>
                <td :class="event.amounthappened < event.amountneeded ? 'not-done' : 'done'"> </td>
                <td>
                    <button @click="updateEvent(event.id, true)" :disabled="event.amountbased&&event.amounthappend >= event.amountneeded">Add 1</button>
                    <button @click="updateEvent(event.id, false)" :disabled="event.amounthappend <= 0">Sub 1</button>
                </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import { fetchBingoEvents, updateBingoEvent } from '../services/bingoService.js';

    export default {
        name: 'EventList',
        data() {
            return {
            events: []
            }
        },
        methods: {
            async fetchEvents() {
            try {
                const result = await fetchBingoEvents();
                this.events = result.data;
            } catch (err) {
                console.error('Error:', err);
            }
            },
            async updateEvent(id, increase) {
                try {
                    await updateBingoEvent(id, increase);
                    this.fetchEvents();
                } catch (err) {
                    console.error('Error:', err);
                }
            }
        },
        mounted() {
            this.fetchEvents();
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
  