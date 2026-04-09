<template>
    <v-container class="game">
        <v-row>
            <v-col cols="1/5" style="text-align:right">Position</v-col>
            <v-col cols="3/5" style="text-align:left">Player</v-col>
            <v-col cols="1/5" style="text-align:right">Score</v-col>
        </v-row>
        <v-container class="overflow-y-auto" height="478px" style="padding: 0">
            <v-row v-for="(player, index) in leaderboard" :key="player.username" :class="getRowClass(index)">
                <v-col cols="1/5" style="text-align:center">{{ index + 1 }}</v-col>
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
            _onEventUpdated() {
                this.prepareLeaderboard();
            },
            
            async prepareLeaderboard() {
                try {
                    const result = await fetchLeaderboard(this.gameId);
                    this.leaderboard = result.data;
                } catch (err) {
                }
            },

            getRowClass(index) {
                const baseClass = 'leaderboard-row';
                if (index === 0) {
                    return baseClass + ' first-place';
                } else if (index === 1) {
                    return baseClass + ' second-place';
                } else if (index === 2) {
                    return baseClass + ' third-place';
                }
                return baseClass;
            }
        },
        mounted() {
            this.prepareLeaderboard();
            window.addEventListener('event-updated', this._onEventUpdated);
        },
		beforeUnmount() {
			window.removeEventListener('event-updated', this._onEventUpdated);
		}
    }
</script>

<style scoped>
    .first-place {
        background-color: gold;
    }

    .second-place {
        background-color: silver;
    }

    .third-place {
        background-color: #cd7f32;
    }

    .leaderboard-row {
        padding-right: 0.5em;
        margin-top: 5px;
        margin-bottom: 5px;
        border: 1px solid var(--text-color);
        border-radius: 4px;
    }
</style>
