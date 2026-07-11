<template>
    <v-container class="contentWrap" v-if="games.length > 0" >
        <v-list class="game">
            <v-list-item v-for="game in games" :key="game.gameid">
                <v-row align="center">
                    <v-col cols="7/8">
                        <v-card link :to="`/game/${game.gameid}`" height="50px" style="padding-top: 13px">
                            {{ game.name }}
                        </v-card>
                    </v-col>
                    <v-col cols="1/8">
                        <v-btn icon="fa-solid fa-bookmark" @click="removeBookmark(game.gameid)"></v-btn>
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
        data() {
            return {
                games: []
            }
        },
        methods: {
            async prepareBingoGames() {
                try {
                    let result = await fetchBingoGames();
                    const bingoGames = result.data;

                    result = await fetchBookmarkedGames();
                    const bookmarkedGames = result.data;
                    this.games = bingoGames.filter(game => bookmarkedGames.some(bookmarkedGame => bookmarkedGame.gameid === game.gameid));
                } catch (e) {
                }
            },

            async removeBookmark(gameId) {
                await updateBookmark(gameId, false);
                this.games = this.games.filter(game => game.gameid !== gameId);
            }
        },
        mounted() {
            this.prepareBingoGames();
        }
    }
</script>
