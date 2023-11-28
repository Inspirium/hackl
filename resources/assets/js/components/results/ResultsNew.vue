<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Rezultati</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <template v-if="!nonmember && !member">
                    <div class="results-search-player mt-3">
                        <a href="#" class="question-circle p-2" @click.prevent="nonmember=true"><span class="heading-small">Igrač nije član kluba</span></a>
                    </div>
                    <div class="mt-3 search-bar">
                        <input class="form-control" type="search" placeholder="Pronađi igrača" id="example-search-input" v-model="search_term">
                        <i class="fa fa-search icon-search" aria-hidden="true"></i>
                        <div class="clearfix"></div>
                    </div>

                    <b-tabs pills nav-class="justify-content-center mt-3" content-class="list-container mt-4">
                        <b-tab title="Svi" active>
                            <a class="list" href="#" @click.prevent="member=player" v-for="player in all_players" :key="player.id" v-if="player.id !== user.id">
                                <div class="list-item">
                                    <div class="heading-num-small align-self-center">
                                    </div>
                                    <div class="list-user">
                                        <img class="avatar-xs mr-2" :src="player.image">
                                        <div class="list-names">
                                            <div class="heading-mid">{{ player.display_name }}</div>
                                            <div class="heading-xs mt-1"><span class="bold">{{player.range}}</span> godina</div>
                                        </div>
                                    </div>
                                    <div class="action">
                                        <div :class="['power-medium', 'power-'+player.rounded_power]">{{ player.rounded_power }}%</div>
                                    </div>
                                </div>
                            </a>
                        </b-tab>
                        <b-tab title="Posljednji protivnici">
                            <a class="list" href="#" @click.prevent="member=player" v-for="player in last_players" :key="player.id">
                                <div class="list-item">
                                    <div class="heading-num-small align-self-center">
                                    </div>
                                    <div class="list-user">
                                        <img class="avatar-xs mr-2" :src="player.image">
                                        <div class="list-names">
                                            <div class="heading-mid">{{ player.display_name }}</div>
                                            <div class="heading-xs mt-1"><span class="bold">{{player.range}}</span> godina</div>
                                        </div>
                                    </div>
                                    <div class="action">
                                        <div :class="['power-medium', 'power-'+player.rounded_power]">{{ player.rounded_power }}%</div>
                                    </div>
                                </div>
                            </a>
                        </b-tab>
                    </b-tabs>
                </template>
                <template v-else>
                    <div class="filter">
                        <div class="filter-box">
                            <div class="filter-item">
                                <div class="filter-label">Tip terena</div>
                                <div class="form-group">
                                    <input type="text" class="form-control bg-white" @click.prevent="showModal('modalSurface')" readonly="true" :placeholder="court.title">
                                    <i class="fa fa-caret-down icon-caret" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="results mt-4">
                        <template v-if="nonmember">
                            <div class="form-group mb-4">
                                <label for="firstname">Protivnik</label>
                                <input type="text" class="form-control" id="firstname" aria-describedby="username" placeholder="Upiši ime protivnika" v-model="nonmember_name">
                            </div>
                        </template>
                        <template v-if="!nonmember">
                            <div class="form-radio">
                                <legend>Dodatne opcije</legend>
                                <input type="radio" id="match-official" :value="true" v-model="official">
                                <label for="match-official">Službeni meč</label>
                                <input type="radio" id="match-friendly" :value="false" v-model="official">
                                <label for="match-friendly">Prijateljski meč</label>
                            </div>
                        </template>
                        <div class="result-box">
                            <div class="result-label">
                                <div class="heading-small mr-auto">{{ date | moment('D.M.Y') }}</div>
                                <div v-if="court" :class="['badge', 'badge-'+court.badge, 'mb-1']">{{ court.title }}</div>
                            </div>
                            <div class="result-item">
                                <div class="result-players">
                                    <img class="avatar-xs mb-2" :src="player_1.image">
                                    <img class="avatar-xs" :src="player_2.image">
                                </div>
                                <div class="result-score-box">
                                    <div class="result-score-label--top">{{ player_1.display_name }}</div>
                                    <div class="result-score">
                                        <div class="result-score-set" v-for="set in sets" v-if="edited && (set[0] || set[1])">
                                            <div :class="['result-score-gem', set[0]>set[1]?'btn-info':'btn-info-light']">{{ set[0] }}</div>
                                            <div :class="['result-score-gem', set[0]<set[1]?'btn-info':'btn-info-light']">{{ set[1] }}</div>
                                        </div>
                                        <div v-if="!edited" class="result-score-set">
                                            <div class="result-score-gem btn-info-light">0</div>
                                            <div class="result-score-gem btn-info-light">0</div>
                                        </div>
                                        <div class="result-score-win" >
                                            <div class="won">
                                                <div class="text-center" v-if="!edited">
                                                    <a class="btn btn-primary btn-lg mt-2" href="#" role="button" @click.prevent="showModal('modalScore')">Upiši rezultat</a>
                                                </div>
                                                <img :class="[winner===true?'align-self-end':'align-self-start']" src="/images/sucess-arrow.svg" width="30" v-if="edited">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="result-score-label--bottom">{{ player_2.display_name}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-box text-center mt-3" v-if="edited">
                        <a class="btn btn-primary btn-lg mr-2" href="#" role="button" @click.prevent="showModal('modalVerify')">Spremi i verificiraj rezultat</a>
                    </div>
                    <div class="btn-box text-center mt-3" v-if="edited">
                        <a class="btn btn-danger mr-2" href="#" role="button" @click.prevent="clearResult">Poništi rezultat</a>
                    </div>
                    <b-modal class="modal_result_input"
                            ref="modalScore"
                            :title="modal_title"
                            ok-variant="danger"
                            ok-title="Gotov meč"
                            :ok-disabled="winner===2"
                            cancel-title="Sljedeći set"
                            cancel-variant="success"
                            @cancel.prevent="nextSet"
                            :cancel-disabled="modal_active_set===4"
                            v-on:hide="hide"
                    >
                        <div class="modal-container-1">
                            <img class="avatar-xs mx-2" :src="player_1.image">
                            <div class="modal-subtitle-player mb-2 text-center">{{ player_1.first_name }}</div>
                            <div :class="['modal-element-white', 'modal-text', sets[modal_active_set][0]===i?'active':'']" v-for="(v, i) in 8" @click="sets[modal_active_set].splice(0, 1, i); edited=true">{{ i }}</div>
                        </div>
                        <div class="modal-container-1 ml-4">
                            <img class="avatar-xs mx-2" :src="player_2.image">
                            <div class="modal-subtitle-player mb-2 text-center">{{player_2.first_name}}</div>
                            <div :class="['modal-element-white', 'modal-text', sets[modal_active_set][1]===i?'active':'']" v-for="(v, i) in 8" @click="sets[modal_active_set].splice(1, 1, i); edited=true">{{ i }}</div>
                        </div>
                        <div class="">
                            <div class="modal-arrow-box-l">
                                <div :class="['modal-arrow-left', !modal_active_set?'inactive':'']" @click="modal_active_set?modal_active_set--:false"></div>
                            </div>
                            <div class="modal-arrow-box-r">
                                <div :class="['modal-arrow-right', modal_active_set===4?'inactive':'']" @click="modal_active_set!==4?modal_active_set++:false"></div>
                            </div>
                        </div>
                    </b-modal>
                    <b-modal class="modal_vertical_center" ref="modalVerify"
                             @ok="hideModal('modalVerify')"
                             title="Verifikacija meča"
                             ok-variant="danger"
                             ok-title="Otkaži"
                             @cancel="verifyResult"
                             cancel-variant="success"
                             cancel-title="U redu">
                        <div class="modal-subtitle" v-if="!nonmember">Vaš partner će dobiti obavijest o rezultatu meča. Nakon verifikacije rezultat će biti prikazan, a vaši bodovi osvježeni.</div>
                        <div class="modal-subtitle" v-if="nonmember">
                            Želite li spremiti ovaj susret? On se neće prikazivati na popisu ostalih rezultata, niti će se bodovati.
                        </div>
                    </b-modal>
                    <b-modal id="modalSurface"
                             ref="modalSurface"
                             title="Podloga"
                             @ok="hideModal('modalSurface')"
                             ok-variant="danger"
                             ok-title="Zatvori"
                             @cancel="hideModal('modalSurface')"
                             cancel-variant="success"
                             cancel-title="U redu"
                    >
                        <div class="modal-container">
                            <div v-for="surface in surfaces" v-bind:class="['modal-element-white', 'modal-text', selected_surface===surface.id?'active':'']" v-on:click="selected_surface = surface.id">{{ surface.title }}</div>
                        </div>
                    </b-modal>

                </template>
            </div>
        </div>



    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        name: "ResultsNew",
        data() {
            return {
                all_players: '',
                last_players: '',
                nonmember: false,
                nonmember_name: '',
                search_term: '',
                member: false,
                sets:[
                    [0,0],
                    [0,0],
                    [0,0],
                    [0,0],
                    [0,0]
                ],
                edited: false,
                modal_active_set: 0,
                date: moment(),
                surfaces: [],
                selected_surface: 6,
                official: true
            }
        },
        computed: {
            court() {
                return _.find(this.surfaces, (o) => {
                    return o.id === this.selected_surface;
                })
            },
            user() {
                return this.$store.state.user;
            },
            player_1() {
                return this.$store.state.user;
            },
            player_2() {
                if (this.member) {
                    return this.member;
                }
                if (this.nonmember) {
                    return {
                        display_name: this.nonmember_name,
                        first_name: this.nonmember_name.split(' ')[0],
                        nonmember: true,
                        image: '/images/user.svg'
                    }
                }
            },
            modal_title() {
                return (this.modal_active_set+1) + '. set';
            },
            winner() {
                if (this.edited) {
                    let p1 = 0, p2 = 0, games = 0;
                    _.forEach(this.sets, (set) => {
                        if (set[0]>set[1]) {
                            p1++;
                            games++;
                        }
                        if (set[0]<set[1]) {
                            p2++;
                            games++
                        }
                    });
                    if (games>1) {
                        if (p1!==p2) {
                            return p1 < p2;
                        }
                    }
                    return 2;
                }
                return 2;
            }
        },
        methods: {
            hide(e) {
                if (e.trigger !== 'ok' && e.trigger !== 'cancel') {
                    this.clearResult();
                }
            },
            clearResult() {
                this.sets = [
                    [0,0],
                    [0,0],
                    [0,0],
                    [0,0],
                    [0,0]
                ];
                this.edited = false;
                this.modal_active_set = 0;
            },
            getPlayers() {
                this.axios.post('players', {term: this.search_term})
                    .then((res) => {
                        this.all_players = res.data.players;
                        this.last_players = res.data.players;
                    });
            },
            showModal(modal) {
                this.$refs[modal].show();
            },
            hideModal(modal) {
                this.$refs[modal].hide();
            },
            verifyResult() {
                this.hideModal('modalVerify');
                this.axios.post('club/result', {court: this.court, date: this.date, sets: this.sets, player_1: this.player_1, player_2: this.player_2, official: this.official})
                    .then((res) => {
                        this.edited = false;
                        this.$router.push(res.data.result.link);
                    });
            },
            getSurfaces() {
                this.axios.get('surfaces')
                    .then((res) => {
                        this.surfaces = res.data;
                    });
            },
            nextSet() {
                if (this.modal_active_set<4) {
                    this.modal_active_set++;
                }
                return false;
            }
        },
        watch: {
            search_term() {
                this.getPlayers();
            }
        },
        mounted() {
            this.getPlayers();
            this.getSurfaces();
           /* window.axios.get('me/players')
                .then((res) => {
                    this.last_players = res.data.players;
                });*/
        },
        beforeRouteLeave (to, from, next) {
            if (this.edited) {
                const answer = window.confirm('Niste spremili rezultat. Sigurno želite otići?')
                if (answer) {
                    next()
                } else {
                    next(false)
                }
            }
        }
    }
</script>

<style scoped>

</style>