<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Pronađi igrača</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="search-bar mt-3">
                    <input class="form-control" type="search" placeholder="Pronađi igrača" id="example-search-input" v-model="search_term">
                    <i class="fa fa-search icon-search" aria-hidden="true"></i>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="list-container mt-2 mb-4">
                    <router-link class="list" :to="'/player/'+player.id" v-for="player in players" :key="player.id">
                        <div class="list-item">
                            <div class="heading-num-small align-self-center">
                                <template v-if="canSelect">
                                    <div class="checkbox">
                                        <input type="checkbox" :id="'player-check-'+player.id" name="player-check" value="yes">
                                        <label :for="'player-check-'+player.id"></label>
                                    </div>
                                </template>
                            </div>
                            <div class="list-user">
                                <img class="avatar-xs mr-2" :src="player.image">
                                <div class="list-names">
                                    <div class="heading-mid">{{ player.display_name }}</div>
                                    <div class="heading-xs mt-1"><span class="bold">{{ player.range }}</span> godina</div>
                                    <div class="heading-xs mt-1" v-if="player.club_member.length">Member</div>
                                </div>
                            </div>
                            <div class="action">
                                <div :class="['power-medium', 'power-'+ player.rounded_power]">{{ player.rounded_power-10 }} - {{ player.rounded_power }}%</div>
                            </div>
                        </div>
                    </router-link>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Players",
        data() {
            return {
                players: []
            }
        },
        methods: {
            getPlayers(modal) {
                this.axios.post('super/players', {term: this.search_term})
                    .then((res) => {
                        this.players = res.data.players;
                    });
                if (modal) {
                    this.hideModal(modal);
                }
            }
        },
        watch: {
            search_term: function() {
                this.getPlayers();
            }
        },
        mounted() {
            this.getPlayers();
        },
        beforeMount() {
            if (this.$store.state.user.id !== 1) {
                this.$router.push('/');
            }
        }
    }
</script>

<style scoped>

</style>