<template>
    <v-container class="game">
        <v-row>
            <v-col cols="1/5" style="text-align:right">Position</v-col>
            <v-col cols="3/5" style="text-align:left">Player</v-col>
            <v-col cols="1/5" style="text-align:right">Score</v-col>
        </v-row>
        <v-container class="overflow-y-auto" height="478px">
            <v-row v-for="player in leaderboard" :key="player.username">
                <v-col cols="1/5" style="text-align:right">{{ leaderboard.indexOf(player) + 1 }}</v-col>
                <v-col cols="3/5" style="text-align:left">{{ player.username }}</v-col>
                <v-col cols="1/5" style="text-align:right">{{ player.score }}</v-col>
            </v-row>
        </v-container>
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
