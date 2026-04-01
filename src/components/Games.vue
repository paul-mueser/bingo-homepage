<template>
    <v-container class="contentWrap">
        <h1>{{ gameStatus === 'upcoming' ? 'Upcoming Games' : gameStatus === 'running' ? 'Running Games' : 'Finished Games' }}</h1>
        <v-list class="game">
            <v-list-item v-for="game in games" :key="game.gameid" link :to="`/game/${game.gameid}`">
                {{ game.name }}
            </v-list-item>
        </v-list>
    </v-container>
</template>

<script>
    import { fetchBingoGames } from '@/services/bingoService';

    export default {
        name: 'GamesView',
        props: {
            gameStatus: String
        },
        data() {
            return {
                games: []
            }
        },
        methods: {
            async prepareBingoGames() {
                try {
                    const result = await fetchBingoGames();
                    const bingoGames = result.data;
                    if (this.gameStatus === 'upcoming') {
                        this.games = bingoGames.filter(game => game.status === 0);
                    } else if (this.gameStatus === 'running') {
                        this.games = bingoGames.filter(game => game.status === 1);
                    } else if (this.gameStatus === 'finished') {
                        this.games = bingoGames.filter(game => game.status === 2);
                    }
                } catch (e) {
                }
            }
        },
        mounted() {
            this.prepareBingoGames();
        }
    }
</script>
