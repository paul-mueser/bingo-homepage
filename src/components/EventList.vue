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
                <td align="center">{{ !event.amountbased ? (event.amounthappened >= event.amountneeded) : event.amounthappened + '/' + event.amountneeded }}</td>
                <td :class="event.amounthappened < event.amountneeded ? (event.amounthappened < 0 ? 'impossible' : 'not-done') : 'done'"> </td>
                <td class="content">
                    <button @click="updateEvent(event.id, true)" :disabled="!event.amountbased && event.amounthappened >= event.amountneeded">Add 1</button>
                    <button @click="updateEvent(event.id, false)" :disabled="event.amounthappened <= -1">Sub 1</button>
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
            }
            },
            async updateEvent(id, increase) {
                try {
                    await updateBingoEvent(id, increase);
                    this.fetchEvents();
                } catch (err) {
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
    .content {
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    white-space: nowrap;
    }
    
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
  
