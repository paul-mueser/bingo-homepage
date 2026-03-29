<template>
    <Games :key="this.currentStatus" :gameStatus="this.currentStatus"/>
</template>

<script>
    import Games from '@/components/Games.vue';

    export default {
        name: 'GamesView',
        props: {
            gameStatus: String
        },
        components: {
            Games
        },
        data() {
            return {
                currentStatus: this.gameStatus
            }
        },
        watch: {
            '$route.params.gameStatus': function(newGameStatus) {
                this.currentStatus = newGameStatus;
            }
        }
    }
</script>
