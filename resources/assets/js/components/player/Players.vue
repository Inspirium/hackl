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
                <div class="filter">
                    <div class="filter-box">
                        <div class="filter-item">
                            <div class="filter-label">Jačina igrača</div>
                            <div class="form-group">
                                <input type="text" class="form-control bg-white" :placeholder="selectedPower" @click.prevent="showModal('modalPower')" readonly="true">
                                <i class="fa fa-caret-down icon-caret" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="filter-item">
                            <div class="filter-label">Dob igrača</div>
                            <div class="form-group">
                                <input type="text" class="form-control bg-white" :placeholder="selectedAge" @click.prevent="showModal('modalAge')" readonly="true">
                                <i class="fa fa-caret-down icon-caret" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="search-bar mt-3">
                    <input class="form-control" type="search" placeholder="Pronađi igrača" id="example-search-input" v-model="search_term">
                    <i class="fa fa-search icon-search" aria-hidden="true"></i>
                    <div class="clearfix"></div>
                </div>
                <div class="summary-display-players">Ukupno članova <span>{{ total }}</span></div>

                <!--     Ovo je verzija kada korisnik označi igrača-->
                <div class="select-dialog mb-3" v-if="canSelect">
                    <div class="heading-mid-dialog">Označeno <span class="">xy igrača</span>
                    </div>
                    <div class="text-center">
                        <a class="btn btn-info mt-2" role="button" href="/players/player-mesages">Pošalji poruku odabranima</a>
                    </div>
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

        <b-modal class="modal_btn_fixed" ref="modalAge"
            title="Odaberi raspon godina"
            @ok="hideModal('modalAge')"
            ok-variant="danger"
            cancel-variant="success"
            @cancel="getPlayers('modalAge')"
            ok-title="Zatvori"
            cancel-title="U redu">
            <div class="modal-container">
                <div v-for="age in ages" v-bind:class="['modal-element-white', 'modal-text', activeAge(age)>-1?'active':'']" v-on:click="activateAge(age)">{{ age }}</div>
            </div>
        </b-modal>
        <b-modal class="modal_btn_fixed" ref="modalPower"
                 title="Odaberi raspon snage"
                 @ok="hideModal('modalPower')"
                 ok-variant="danger"
                 cancel-variant="success"
                 @cancel="getPlayers('modalPower')"
                 ok-title="Zatvori"
                 cancel-title="U redu">
            <div class="modal-container">
                <div v-for="power in powers" v-bind:class="['modal-element-white', 'modal-text', activePower(power)>-1?'active':'']" v-on:click="activatePower(power)">{{ power }}</div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "Players",
        data() {
            return {
                players: [],
                canSelect: false,
                ages: [ 'Sve', '0-10', '10-20', '20-30', '30-40', '40-50', '50-60', '60-70', '70-80', '80-90', '90-100'],
                selected_age: ['Sve'],
                powers: [
                    'Sve', '0-10%', '10-20%', '20-30%','30-40%','40-50%','50-60%','60-70%','70-80%','80-90%', '90-100%'
                ],
                selected_power: ['Sve'],
                search_term: '',
                offset: 0,
                bottom: false,
                nomore: false,
                total: 0
            }
        },
        computed: {
            selectedAge() {
                if (this.selected_age.indexOf('Sve')>-1) {
                    return 'Sve';
                }
                let ages = this.selected_age;
                ages.sort();
                let age1 = ages[0].split('-')[0];
                let age2 = ages[ages.length-1].split('-')[1];
                return age1 + '-' + age2;
            },
            selectedPower() {
                if (this.selected_power.indexOf('Sve')>-1) {
                    return 'Sve';
                }
                let powers = this.selected_power;
                powers.sort();
                let power1 = powers[0].split('-')[0];
                let power2 = powers[powers.length-1].split('-')[1];
                return power1 + '-' + power2;
            },
        },
        methods: {
            activePower(power) {
                return this.selected_power.indexOf(power);
            },
            activatePower(power) {
                this.offset = 0;
                if (power === 'Sve') {
                    this.selected_power = ['Sve'];
                    return;
                }
                if (this.selected_power.indexOf('Sve')>-1) {
                    this.selected_power = [power];
                    return;
                }
                if (this.activePower(power)>-1) {
                    if (this.selected_power.length > 1) {
                        this.selected_power.splice(this.activePower(power), 1);
                        return;
                    }
                    else {
                        this.selected_power = ['Sve'];
                        return;
                    }
                }
                this.selected_power.push(power);
            },
            activeAge(age) {
                return this.selected_age.indexOf(age);
            },
            activateAge(age) {
                this.offset = 0;
                if (age === 'Sve') {
                    this.selected_age = ['Sve'];
                    return;
                }
                if (this.selected_age.indexOf('Sve')>-1) {
                    this.selected_age = [age];
                    return;
                }
                if (this.activeAge(age)>-1) {
                    if (this.selected_age.length > 1) {
                        this.selected_age.splice(this.activeAge(age), 1);
                        return;
                    }
                    else {
                        this.selected_age = ['Sve'];
                        return;
                    }
                }
                this.selected_age.push(age);
            },
            showModal(modal, index) {
                this.$refs[modal].show();
            },
            hideModal(modal) {
                this.$refs[modal].hide();
            },
            getPlayers(modal) {
                this.$store.commit('spinner');
                this.axios.post('players', {power: this.selectedPower, age:this.selectedAge, term: this.search_term, offset: this.offset, is_club: this.$route.path.indexOf('club')>-1 })
                    .then((res) => {
                        if (this.offset !== 0) {
                            if (res.data.players.length) {
                                this.players = [...this.players, ...res.data.players];
                            }
                            else {
                                this.nomore = true;
                            }
                        }
                        else {
                            this.players = res.data.players;
                            this.total = res.data.total;
                        }
                        this.$store.commit('spinner');
                    });
                if (modal) {
                    this.hideModal(modal);
                }
            },
            bottomVisible() {
                return ((window.innerHeight + window.pageYOffset+200) >= document.body.offsetHeight)
            },
        },
        watch: {
            search_term(term) {
                if (term.length) {
                    document.getElementById('app').scrollTop = 0;
                    this.offset = 0;
                    this.debouncedGetPlayers()
                }
            },
            bottom(bottom) {
                if (bottom && !this.nomore) {
                    this.offset +=20;
                    this.getPlayers();
                }
            }

        },
        mounted() {
            window.addEventListener('scroll', () => {
                this.bottom = this.bottomVisible()
            });
            this.offset = 0;
            this.getPlayers();
        },
        created() {
            this.debouncedGetPlayers = _.debounce(this.getPlayers, 500)
        }
    }
</script>
