<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Pošalji poruku</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button class="btn btn-primary" @click="showModal('modalPlayers')">Dodaj igrače</button>
                <div class="message-multi-container mt-2" v-if="players.length">
                    <div class="heading-mid-dialog">
                        <span class="d-inline-block mb-2">{{ players.length }} igrača</span>
                    </div>
                    <div class="message-multi-players">
                        <img class="avatar-xs" :src="player.image" v-for="player in players">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-radio">
                    <legend class="mt-3 mb-1">Napiši poruku</legend>
                    <textarea class="form-control" id="message" rows="6" v-model="new_message"></textarea>
                </div>
                <div class="text-center btn-box mt-4 mb-1">
                    <a class="btn btn-primary" href="#" role="button" @click.prevent="submitMessage">Pošalji</a>
                </div>
            </div>
        </div>

        <b-modal class="modal_addon_player" ref="modalPlayers"
                 title="Dodaj igrača"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="U redu"
        >
            <div class="modal-container">
                <div class="modal-subtitle">
                    Dodaj druge igrače
                </div>
                <div class="search-bar mt-3">
                    <input class="form-control" type="search" placeholder="Pronađi igrača" id="example-search-input" v-model="search_term">
                    <i class="fa fa-search icon-search" aria-hidden="true"></i>
                    <div class="clearfix"></div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="list-container mt-2 mb-4">
                            <a class="list" v-for="player in filter_players" :key="player.id" @click.prevent="addPlayer(player)">
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
                                        <div :class="['power-medium', 'power-'+ player.rounded_power]">{{ player.rounded_power-10 }} - {{ player.rounded_power }}%</div>
                                    </div>
                                </div>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "ThreadStart",
        data() {
            return {
                filter_players: [],
                players: [],
                new_message: '',
                search_term: ''
            }
        },
        methods: {
            submitMessage() {
                this.axios.post('thread', {players: this.players, message: this.new_message})
                    .then((res) => {
                        this.$router.push(res.data.link);
                    })
            },
            showModal(modal, index) {
                this.$refs[modal].show();
                this.selected_court = index;
            },
            hideModal(modal, next) {
                this.$refs[modal].hide();
                if (next) {
                    this.$refs[next].show();
                }
            },
            getPlayers() {
                this.axios.post('players', {power: 'Sve', age:'Sve', term: this.search_term, offset: this.offset})
                    .then((res) => {
                        this.filter_players = res.data.players;
                    });
            },
            addPlayer(player) {
                this.players.push(player);
                this.hideModal('modalPlayers');
            }
        },
        mounted() {

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
        }
    }
</script>