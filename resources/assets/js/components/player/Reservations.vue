<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Moje rezervacije</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">

                <template v-for="reservation in reservations">
                    <div class="text-center mt-4">
                        <div class="heading-mid">{{ reservation.from | moment("dddd, D.M.") }}</div>
                        <div class="heading-big-bigger">{{ reservation.from | moment("h:mm") }} — {{ reservation.to | moment("h:mm") }}</div>
                    </div>

                    <div class="court mt-3">
                        <div class="court-header d-flex">
                            <div class="heading-small mr-auto">{{ reservation.court.name }}</div>
                            <div v-bind:class="['badge', 'badge-'+reservation.court.surface.badge, 'mb-1']">{{ reservation.court.surface.title }}</div>
                        </div>
                        <div class="list-container mb-2">
                                    <router-link class="list" :to="'/player/'+player.id" v-for="player in reservation.players" :key="player.id">
                                        <div class="list-item">
                                            <div class="heading-num-small align-self-center">
                                            </div>
                                            <div class="list-user">
                                                <img class="avatar-xs mr-2" :src="player.image">
                                                <div class="list-names">
                                                    <div class="heading-mid">{{ player.display_name }}</div>
                                                    <div class="heading-xs mt-1"><span class="bold">{{ player.range }}</span> godina</div>
                                                </div>
                                            </div>
                                            <div class="action">
                                                <div class="btn btn-sm btn-danger" v-if="player.id !== user.id" @click.prevent="deletePlayer(reservation, player)">Obriši</div>
                                            </div>
                                        </div>
                                    </router-link>

                                </div>

                        <div class="text-center btn-box mt-2">
                            <a class="btn btn-danger" href="#" v-on:click.prevent="showModal('deleteReservation', reservation.id)">Obriši rezervaciju</a>
                            <a class="btn btn-info" href="#" v-on:click.prevent="showModal('addPlayer', reservation.id)">Dodaj suigrača</a>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <b-modal ref="addPlayer"
                 title="Dodaj suigrača"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="U redu"
        >
            <div class="modal-container">
                <div class="modal-subtitle">
                   Dodaj suigrača na rezervaciju.
                </div>
                <div class="search-bar mt-3">
                    <input class="form-control" type="search" placeholder="Pronađi igrača" id="example-search-input" v-model="search_term">
                    <i class="fa fa-search icon-search" aria-hidden="true"></i>
                    <div class="clearfix"></div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="list-container mt-2 mb-4">
                            <a class="list" v-for="player in players" :key="player.id" @click.prevent="invitePlayer(player)" v-if="player.id !== user.id">
                                <div class="list-item">
                                    <div class="heading-num-small align-self-center">
                                        <template v-if="false">
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
                                        </div>
                                    </div>
                                    <div class="action">
                                        <div :class="['power-medium', 'power-'+ player.rounded_power]">{{ player.rounded_power-10 }} - {{ player.rounded_power }}%</div>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
        <b-modal ref="deleteReservation"
                 title="Obriši rezervaciju?"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="Obriši"
                 @cancel="deleteReservation"
        >
            <div class="modal-container">
                <div class="modal-subtitle">Jeste li sigurni da želite obrisati rezervaciju?</div>

            </div>
        </b-modal>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                reservations: [],
                players: [],
                search_term: '',
                selected_reservation: null
            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            }
        },
        methods: {
            getPlayers() {
                this.axios.post('players', {power: 'Sve', age:'Sve', term: this.search_term, offset: this.offset})
                    .then((res) => {
                        this.players = res.data.players;
                    });
            },
            invitePlayer(player) {
                this.axios.post('club/reservation/'+this.selected_reservation+'/friend', {player: player.id})
                    .then(() => {
                        this.loadReservations();
                        this.$refs.addPlayer.hide();
                        this.selected_reservation = null;
                    })
            },
            showModal(modal, index) {
                this.$refs[modal].show();
                this.selected_reservation = index;
            },
            hideModal(modal, next) {
                this.$refs[modal].hide();
                if (next) {
                    this.$refs[next].show();
                }
            },
            loadReservations() {
                this.axios.get('player/'+this.user.id+'/reservations')
                    .then((res) => {
                        this.reservations = res.data.reservations;
                    })
            },
            deleteReservation() {
                this.axios.delete('club/reservation/'+this.selected_reservation)
                    .then(() => {
                        this.selected_reservation = null;
                        this.loadReservations();
                    })
            },
            deletePlayer(reservation, player) {
                this.axios.delete('club/reservation/'+reservation.id+'/friend/'+player.id)
                    .then(() => {
                         reservation.players = _.filter(reservation.players, (p) => {
                             return p.id !== player.id;
                         });
                    });
            }
        },
        mounted() {
            this.loadReservations();
        },
        watch: {
            search_term(term) {
                if (term.length) {
                    this.debouncedGetPlayers()
                }
            }
        },
        created() {
            this.debouncedGetPlayers = _.debounce(this.getPlayers, 500)
        },
    }
</script>