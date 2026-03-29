<template>
    <v-container class="game">
        <v-row>
            <v-col cols="1/2">Player</v-col>
            <v-col cols="1/2">Score</v-col>
        </v-row>
        <v-row v-for="player in leaderboard" :key="player.username">
            <v-col cols="1/2">{{ player.username }}</v-col>
            <v-col cols="1/2">{{ player.score }}</v-col>
        </v-row>
    </v-container>
</template>

<script>
    import { fetchLeaderboard } from '@/services/bingoService.js';

    export default {
        name: 'Leaderboard',
        props: {
            gameId: String
        },
        data() {
            return {
				leaderboard: [],
            }
        },
        methods: {
            async prepareLeaderboard() {
                try {
                    const result = await fetchLeaderboard(this.gameId);
                    this.leaderboard = result.data;
                } catch (err) {
                }
            },
        },
        mounted() {
            this.prepareLeaderboard();
        }
    }
</script>
