<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Rezervacija terena</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <!--Prikaži samo ako je admin objavio prekid igre zbog atmosferlija-->
                <div v-if="club.canceled[selected_date]" class="heading-section full-width text-danger my-3">Igra prekinuta</div>
                <!--End-->
                <template v-if="isAdmin">
                <div class="text-center mb-4 mt-2">
                    <a class="btn btn-secondary btn-lg mt-3 rain-icon" @click.prevent="showModal('modalWeather')">Atmosferske neprilike
                    </a>
                </div>

                <!--Prikaži samo ako je admin objavio prekid igre zbog atmosferlija-->
                <div class="text-center mb-4 mt-2" v-if="club.weather">
                    <a class="btn btn-info btn-lg mt-3" @click.prevent="showModal('modalWeather')">Nastavak igre
                    </a>
                </div>
                </template>


                <!--Class .inactive is for disabled arrow - user can't go to past dates -->
                <div class="date-picker mt-2">
                    <i v-bind:class="['fa fa-angle-left fa-4x', inactive?'inactive':'']" v-on:click="prevDay"></i>
                    <div class="heading-date">{{ selected_date | moment("dddd") }}, <span>{{ selected_date | moment("DD.MM.") }}</span></div>
                    <i class="fa fa-angle-right fa-4x" v-on:click="nextDay"></i>
                </div>

                <div class="court mt-4" v-for="(court, index) in courts">
                    <router-link :to="'/club/court/'+court.id+'/'+selected_date.format('YYYY-MM-DD')">
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
                        <div class="text-center btn-box mt-3">
                            <a class="btn btn-primary" href="#" v-on:click.prevent="showModal('modalTimetable', index)">Rezerviraj ovaj teren</a>
                            <!--Admin-only-->
                            <router-link v-if="isAdmin" class="btn btn-info" v-bind:to="'/admin/court/'+court.id">Uredi podatke</router-link>
                            <!--End-->
                        </div>
                    </router-link>
                </div>
                <!--Admin-only-->
                <div class="text-center btn-box mt-3" v-if="isAdmin">
                    <router-link class="btn btn-primary btn-lg" to="/admin/court-new" role="button">Dodaj novi teren</router-link>
                </div>
                <!--End-->
            </div>
        </div>
        <b-modal id="modalSurface"
                 ref="modalSurface"
                 title="Podloga"
                 @ok="hideModal('modalSurface')"
                 ok-variant="danger"
                 ok-title="Zatvori"
                 @cancel="hideModal('modalSurface', 'modalTimetable')"
                 cancel-variant="success"
                 cancel-title="U redu">
            <div class="modal-container">
                <div v-bind:class="['modal-element-white', 'modal-text', selected_surface==='any'?'active':'']" v-on:click="selected_surface = 'any'">Bilo koja podloga</div>
                <div v-for="surface in surfaces" v-bind:class="['modal-element-white', 'modal-text', selected_surface===surface.designation?'active':'']" v-on:click="selected_surface = surface.designation">{{ surface.title }}</div>
            </div>
        </b-modal>
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
                        <div class="modal-text modal-price">{{ hour.time | parseTime(true) }} - {{ hour.time+ (court.reservation_duration === 30?50:100) | parseTime(true) }}</div>
                        <div class="modal-text mx-2"><i class="fa fa-angle-right text-danger"></i></div>
                        <div class="modal-text"> {{ hour.price }} kn</div>
                    </div>
                </template>
            </div>
        </b-modal>
        <b-modal ref="reservationConfirmation"
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
        <b-modal ref="modalWeather"
                 title="Obustava igre"
                ok-title="Zatvori"
                 cancel-title="U redu"
                 ok-variant="danger"
                 cancel-variant="success"
        >
            <div class="modal-container">
                <div class="modal-subtitle">Svi članovi koji danas imaju rezervirane terene primiti će obavijest </div>
                <div class="modal-element-white modal-text" @click="weather('all')">Svi</div>
                <div class="modal-element-white modal-text" @click="weather('outside')">Samo vanjski tereni</div>
                <div class="modal-element-white modal-text" @click="weather('inside')">Samo unutrašnji tereni</div>
            </div>
        </b-modal>
    </div>
</template>
<script>
    import moment from 'moment';
    export default {
        data: function () {
            return {
                selected_date: moment(),
                selected_times: [],
                selected_court: 0,
                inactive: true,
                types: {
                    A: {
                        badge: '',
                        title: 'Akril',
                        fill: '',
                        reserved: ''
                    },
                    B: {
                        badge: '',
                        title: 'Umjetna zemlja',
                        fill: '',
                        reserved: ''
                    },
                    C: {
                        badge: '',
                        title: 'Umjetna trava',
                        fill: '',
                        reserved: ''
                    },
                    D: {
                        badge: '',
                        title: 'Asfalt',
                        fill: '',
                        reserved: ''
                    },
                    E: {
                        badge: '',
                        title: 'Tepih',
                        fill: '',
                        reserved: ''
                    },
                    F: {
                        badge:'badge-clay',
                        title: 'Zemlja',
                        fill: '#B25900',
                        reserved: 'clay-reserve'
                    },
                    G: {
                        badge:'bg-primary',
                        title: 'Beton',
                        fill: '#0085B2',
                        reserved: ''
                    },
                    H: {
                        badge: '',
                        title: 'Trava',
                        fill: '',
                        reserved: ''
                    },
                    J: {
                        badge: '',
                        title: 'Drugo',
                        fill: '',
                        reserved: ''
                    },
                },
                players: [],
                search_term: '',
                reservation: 0,
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
            club() {
                return this.$store.state.club;
            },
            courts() {
                return this.$store.state.club.courts;
            },
            surfaces() {
                return this.$store.state.club.surfaces;
            },
            selected_surface: {
                get() { return this.$store.state.club.filters.surface; },
                set(value) { this.$store.commit('club/update_court_filter', {filter: 'surface', value: value}); this.hideModal('modalSurface', 'modalTimetable') }
            },
            court() {
                return this.courts[this.selected_court];
            }
        },
        methods: {
            weather(type) {
                if (this.isAdmin) {
                    this.axios.put('club/weather', {type: type})
                        .then(() => {

                        });
                }
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
            nextDay() {
                this.selected_date = moment(this.selected_date).add(1, 'day');
                if (this.selected_date.isAfter(moment())) {
                    this.inactive = false;
                }
                this.$store.dispatch('club/get_courts', this.selected_date.format("YYYY-MM-DD"));
            },
            prevDay() {
                if (!this.inactive) {
                    this.selected_date = moment(this.selected_date).subtract(1, 'day');
                    if (this.selected_date.isSame(moment(), 'day')) {
                        this.inactive = true;
                    }
                }
                this.$store.dispatch('club/get_courts', this.selected_date.format("YYYY-MM-DD"));
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
                    this.$store.dispatch('club/submit_times', {court_id: this.court.id, times:this.selected_times, date: this.selected_date.format('YYYY-MM-DD')}).then((res) => {
                        this.selected_times = [];
                        this.reservation = res.reservation;
                        this.$refs.modalTimetable.hide();
                        this.$refs.reservationConfirmation.show();

                    }).catch((err) => {
                        if (err.response.status === 409) {
                            this.errors = err.response.data.errors;
                            this.canRepeat = false;
                            this.$refs.repeatReservation.hide();
                            this.$refs.reservationTaken.show();
                        }
                    });
                }

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
                        this.$refs.reservationConfirmation.hide();
                    })
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
        watch: {
            search_term(term) {
                if (term.length) {
                    this.debouncedGetPlayers()
                }
            }
        },
        mounted() {
            this.$store.dispatch('club/get_courts', '');
        },
        created() {
            this.debouncedGetPlayers = _.debounce(this.getPlayers, 500)
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
    }
</script>