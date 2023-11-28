<template>
    <div class="container" v-if="court.id">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Tereni</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="court mt-4">
                        <div class="court-header d-flex">
                            <div class="heading-small mr-auto">{{ court.name }}</div>
                            <div v-bind:class="['badge', 'badge-'+court.surface.badge, 'mb-1']">{{ court.surface.title }}</div>
                        </div>
                        <div class="court-slots d-flex">
                            <div class="court-slot" v-for="hour in court.parsed_reservations" v-if="!(hour.time % 100)">
                                <div class="heading-num-small text-center">{{ hour.time | parseTime(false) }}</div>

                                <template v-if="hour.player || (court.reservation_duration === 30 && court.parsed_reservations[hour.time + 50].player)">
                                    <div v-bind:class="['indicator', ((court.reservation_duration === 60 && hour.player) || ( court.reservation_duration === 30 && hour.player && court.parsed_reservations[hour.time+50].player))?court.surface.reserved:'']">
                                        <svg v-if="(court.reservation_duration === 30 && court.parsed_reservations[hour.time+50].player)" width="100%" height="100%" viewBox="0 0 10 10" preserveAspectRatio="none">
                                            <polygon v-bind:fill="court.surface.fill" stroke-width=0 points="0,10 10,10 10,0" />
                                        </svg>
                                        <svg v-if="court.reservation_duration === 30 && !court.parsed_reservations[hour.time+50].player" width="100%" height="100%" viewBox="0 0 10 10" preserveAspectRatio="none">
                                            <polygon v-bind:fill="court.surface.fill" stroke-width=0 points="0 0,0 10,10 0"/>
                                        </svg>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="indicator"></div>
                                </template>
                            </div>
                        </div>
                </div>
            </div>
        </div>

        <!-- Table list -->
        <div class="row">
            <div class="col">
                <div class="list-container mt-4 mb-4">
                    <div class="list" v-for="one in court.parsed_reservations">
                        <div class="list-item">
                            <div class="heading-num-small align-self-center mr-2">{{ one.time | parseTime(true) }}</div>
                            <div class="list-user">
                                <template v-if="one.player">
                                    <router-link :to="'/player/'+one.player.id"  class="list-user">
                                        <img class="avatar-xs mr-2" :src="one.player.image">
                                        <div class="heading-mid align-self-center">{{ one.player.display_name }}</div>
                                    </router-link>
                                </template>
                                <template v-if="one.player2">
                                    <router-link :to="'/player/'+one.player2.id"  class="list-user">
                                        <img class="avatar-xs mr-2" :src="one.player2.image">
                                        <div class="heading-mid align-self-center">{{ one.player2.display_name }}</div>
                                    </router-link>
                                </template>
                            </div>
                            <div class="action">
                                <!--Admin-only-->
                                <a class="btn btn-success align-self-center mr-2" href="#" role="button" data-toggle="modal" data-target="#modal-discount" v-if="false">Promo</a>
                                <!--End-->
                                <a class="btn btn-info align-self-center" href="#" role="button" @click.prevent="addPlayerModal(one.id)" v-if="one.id && canAddPlayer(one)">Dodaj igrača</a>
                                <a class="btn btn-danger align-self-center" href="#" role="button" @click.prevent="warnCancel(one.id)" v-if="one.id && canCancel(one)">Otkaži</a>
                                <a class="btn btn-info align-self-center" href="#" role="button" v-if="!one.player" @click.prevent="showModal('modalTimetable')">Rezerviraj</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-modal ref="modalTimetable"
                 title="Vrijeme"
                 @ok="hideModal('modalTimetable')"
                 ok-variant="danger"
                 cancel-variant="success"
                 @cancel="submitTimes"
                 ok-title="Zatvori"
                 cancel-title="U redu">
            <div class="modal-container">
                <template v-for="hour in court.parsed_reservations">
                    <div v-bind:class="getClass(hour.time)"
                         v-on:click="selectTime(hour.time)">
                        <div class="modal-text modal-price">{{ hour.time | parseTime(true) }} - {{ hour.time+(court.reservation_duration === 30?50:100) | parseTime(true) }}</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger"></i></div>
                        <div class="modal-text"> {{ hour.price }} kn</div>
                    </div>
                </template>
            </div>
        </b-modal>
        <b-modal class="modal_vertical_center" ref="modalCancel"
                 title="Jeste li sigurni da želite otkazati rezervaciju?"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Ne"
                 cancel-title="Da"
                 @cancel="cancelTerm"
        >
            <div class="modal-subtitle">U slučaju potvrde otkaza rezervacije poruka će biti poslana korisniku na e-mail</div>
        </b-modal>
        <b-modal class="modal_addon_player" ref="reservationConfirmation"
                 title="Potvrda rezervacija"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="U redu"
        >
            <div class="modal-container">
                <div class="modal-subtitle">
                    Vaša rezervacija je uspješna!<br>
                    Obavijesti suigrača o rezervaciji.
                </div>
                <div class="search-bar mt-3">
                    <input class="form-control" type="search" placeholder="Pronađi igrača" id="example-search-input" v-model="search_term">
                    <i class="fa fa-search icon-search" aria-hidden="true"></i>
                    <div class="clearfix"></div>
                </div>

                <a href="#" @click.prevent="hideModal('reservationConfirmation', 'repeatReservation')" role="button" class="btn btn-info align-self-center">Želim ponavljajući termin</a>

                <div class="row">
                    <div class="col">
                        <div class="list-container mt-2 mb-4">
                            <a class="list" v-for="player in players" :key="player.id" @click.prevent="invitePlayer(player.id)" v-if="player.id !== user.id">
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
        <b-modal ref="repeatReservation"
                 title="Ponavljajuća rezervacija"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="U redu"
                 @cancel="repeatReservation"
        >
            <div class="modal-container">
                <div class="modal-subtitle">
                    Odaberite koliko sljedećih istovjetnih termina želite rezervirati? Kasnije na "Moje rezervacije" možete pozvati suigrače.
                </div>
                <template v-for="n in 10">
                    <div v-bind:class="getClassSelected(n, 'repeat')"
                         v-on:click="clickSelected(n, 'repeat', false)">
                        <div class="modal-text modal-price">{{ n }}</div>
                    </div>
                </template>
            </div>
        </b-modal>
        <b-modal ref="reservationTaken"
                 title="Nemoguća rezervacija"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Odustani"
                 cancel-title="Nastavi"
                 @cancel="confirmReservation"
        >
            <div class="modal-container">
                <div class="modal-subtitle">
                    Sljedeći termini su već zauzeti i nije ih moguće rezervirati.
                    <span v-for="error in errors">{{ error }}</span>
                </div>

            </div>
        </b-modal>
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
                    <input class="form-control" type="search" placeholder="Pronađi igrača" v-model="search_term">
                    <i class="fa fa-search icon-search" aria-hidden="true"></i>
                    <div class="clearfix"></div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="list-container mt-2 mb-4">
                            <a class="list" v-for="player in players" :key="player.id" @click.prevent="invitePlayer(player.id)" v-if="player.id !== user.id">
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
    </div>
</template>
<script>
    import moment from 'moment';
    export default {
        data: function () {
            return {
                court: {},
                reservations: [],
                selected_times: [],
                cancel_term: 0,
                search_term: '',
                reservation: 0,
                players: [],
                repeat: null,
                errors: [],
                canRepeat: false
            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            },
            isAdmin() {
                return this.$store.getters['user/isAdmin'];
            },
            date() {
                if (this.$route.params.date) {
                    return this.$route.params.date;
                }
                return moment().format('YYYY-MM-DD');
            }
        },
        methods: {
            canCancel(reservation) {
                if (this.isAdmin) {
                    return true;
                }
                return false;
                if (this.user.id === reservation.player.id) {
                    let hour = reservation.time/100;
                    let date = moment(this.$route.params.date);
                    date.add(hour, 'h');
                    let now = moment().add(5, 'h');
                    return now.isBefore(date);
                }
                return false;
            },
            canAddPlayer(reservation) {
                return this.user.id === reservation.player.id;
            },
            addPlayerModal(reservation) {
                this.reservation = reservation;
                this.showModal('addPlayer');
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
            getClass(hour) {
                return {
                    'modal-element-white': true,
                    'modal-element-gray': (this.court.parsed_reservations[hour].player),
                    'active': _.indexOf(this.selected_times, hour) > -1
                }
            },
            selectTime(hour, part) {
                if (this.court.parsed_reservations[hour].player) {
                    return;
                }
                if (_.indexOf(this.selected_times, hour)>-1) {
                    this.selected_times.splice(_.indexOf(this.selected_times, hour), 1);
                }
                else {
                    this.selected_times.push(hour);
                }
            },
            submitTimes() {
                if (this.selected_times.length) {
                    this.$store.dispatch('club/submit_times', {court_id: this.court.id, times:this.selected_times, date: this.date})
                        .then((res) => {
                            this.getCourt();
                            this.selected_times = [];
                            this.reservation = res.reservation;
                            this.$refs.modalTimetable.hide();
                            this.$refs.reservationConfirmation.show();
                        })
                        .catch(() => {
                        //this.$router.push('login');
                    });
                }
            },
            getCourt() {
                this.axios.get('club/court/'+this.$route.params.id+'/'+this.$route.params.date)
                    .then((res) => {
                        this.court = res.data.court;
                        this.court.parsed_reservations = res.data.reservations;
                    });
            },
            cancelTerm() {
                if (this.cancel_term) {
                    this.axios.delete('club/reservation/'+this.cancel_term)
                        .then((res) => {
                            this.getCourt();
                        })
                }
            },
            warnCancel(id) {
                this.cancel_term = id;
                this.showModal('modalCancel');
            },
            getPlayers() {
                this.axios.post('players', {power: 'Sve', age:'Sve', term: this.search_term, offset: this.offset})
                    .then((res) => {
                        this.players = res.data.players;
                    });
            },
            invitePlayer(id) {
                this.axios.post('club/reservation/'+this.reservation+'/friend', {player: id})
                    .then(() => {
                        this.getCourt();
                        this.$refs.reservationConfirmation.hide();
                        this.$refs.addPlayer.hide();
                    })
            },
            getClassSelected(value, vars) {
                return {
                    'modal-element-white': true,
                    'active': _.indexOf(this.$data[vars], value) > -1
                }
            },
            clickSelected(value, vars, multiple) {
                if (_.indexOf(this.$data[vars], value) > -1) {
                    this.$data[vars].splice(_.indexOf(this.$data[vars], value), 1);
                }
                else {
                    if (multiple) {
                        this.$data[vars].push(value);
                    }
                    else {
                        this.$data[vars] = [value];
                    }
                }

            },
            repeatReservation() {
                this.axios.post('club/reservation/'+this.reservation+'/repeat', {times: this.repeat, repeat: this.canRepeat})
                    .then(() => {
                        this.$router.push('/me/reservations')
                    })
                    .catch((err) => {
                        if (err.response.status === 409) {
                            this.errors = err.response.data.errors;
                            this.canRepeat = true;
                            this.$refs.modalTimetable.hide();
                            this.$refs.reservationTaken.show();
                        }
                    });
            },
            confirmReservation() {
                if (this.canRepeat) {
                    this.repeatReservation();
                    this.errors = [];
                    this.canRepeat = false;
                }
                this.$refs.reservationTaken.hide();
            }
        },
        filters: {
            parseTime(value, m) {
                let hour = Math.floor(value/100);
                let minutes = (value % 100) / 100 * 60;
                if (minutes === 0) {
                    minutes = '00';
                }
                if (m) {
                    return hour + ':' + minutes;
                }
                return hour;
            }
        },
        mounted() {
            this.getCourt();
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