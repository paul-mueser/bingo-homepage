<template>
    <v-container class="contentWrap">
        <h1>{{ gameStatus === 'upcoming' ? 'Upcoming Games' : gameStatus === 'running' ? 'Running Games' : 'Finished Games' }}</h1>
        <v-list class="game">
            <v-list-item v-for="game in games" :key="game.gameid">
                <v-row align="center">
                    <v-col cols="9/10">
                        <v-card link :to="`/game/${game.gameid}`" height="50px" style="padding-top: 13px">
                            {{ game.name }}
                        </v-card>
                    </v-col>
                    <v-col :key="game.bookmarked" cols="1/10">
                        <v-btn icon="fa-regular fa-bookmark" @click="addBookmark(game.gameid)" v-if="!game.bookmarked"></v-btn>
                        <v-btn icon="fa-solid fa-bookmark" @click="removeBookmark(game.gameid)" v-else></v-btn>
                    </v-col>
                </v-row>
            </v-list-item>
        </v-list>
    </v-container>
</template>

<script>
    import { fetchBingoGames, fetchBookmarkedGames, updateBookmark } from '@/services/bingoService';

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

                try {
                    const result = await fetchBookmarkedGames();
                    const bookmarkedGames = result.data;
                    this.games.forEach(game => {
                        game.bookmarked = bookmarkedGames.some(bookmarkedGame => bookmarkedGame.gameid === game.gameid);
                    });
                } catch (e) {
                    this.games.forEach(game => {
                        game.bookmarked = false;
                    });
                }
            },

            async addBookmark(gameId) {
                await updateBookmark(gameId, true);
                this.games.forEach(game => {
                    if (game.gameid === gameId) {
                        game.bookmarked = true;
                    }
                });
            },

            async removeBookmark(gameId) {
                await updateBookmark(gameId, false);
                this.games.forEach(game => {
                    if (game.gameid === gameId) {
                        game.bookmarked = false;
                    }
                });
            }
        },
        mounted() {
            this.prepareBingoGames();
        }
    }
</script>
