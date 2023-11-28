<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image-ranks">
                    <div class="gradient"></div>
                    <div class="heading-image">Rang ljestvica</div>
                </div>
            </div>
        </div>

        <div class="filter">
            <div class="filter-box">
                <div class="filter-item">
                    <div class="filter-label">Dobna kategorija</div>
                    <div class="form-group">
                        <input type="text" class="form-control bg-white" v-model="selected_age" readonly="true" @click.prevent="showModal('modalAge')">
                        <i class="fa fa-caret-down icon-caret" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="heading-mid-dialog my-3 c-gray-light">Moja pozicija</div>
        <div class="list-container mb-2">
            <div class="list">
                <div class="list-item">
                    <div class="align-self-center">
                        <div class="heading-big mr-3">{{ user.rank_club }}.</div>
                    </div>
                    <div class="list-user">
                        <img class="avatar-xs mr-2" :src="user.image">
                        <div class="list-names">
                            <div class="heading-mid">{{ user.display_name }}</div>
                            <div class="heading-xs mt-1"><span class="bold">{{ user.range }}</span> godina</div>
                        </div>
                    </div>
                    <div class="action">
                        <div :class="['power-medium', 'power-'+user.rounded_power]">{{ user.score }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!--Tabs-->
        <b-tabs pills nav-class="justify-content-center mt-3" content-class="list-container mt-3 mb-4">
            <b-tab title="Ukupno" active>
                <router-link class="list" :to="'/player/'+player.id" v-for="(player,index) in club_players" :key="player.id">
                    <div class="list-item">
                        <div class="align-self-center">
                            <div class="heading-big mr-3">{{ player.rank_club }}.</div>
                        </div>
                        <div class="list-user">
                            <img class="avatar-xs mr-2" :src="player.image">
                            <div class="list-names">
                                <div class="heading-mid">{{ player.display_name }}</div>
                                <div class="heading-xs mt-1"><span class="bold">{{ player.range }}</span> godina</div>
                            </div>
                        </div>
                        <div class="action">
                            <div :class="['power-medium', 'power-'+player.rounded_power]">{{ player.score }}</div>
                        </div>
                    </div>
                </router-link>
            </b-tab>
            <b-tab title="Omjeri">
                <router-link class="list" :to="'/player/'+player.id" v-for="(player,index) in club_players" :key="player.id">
                    <div class="list-item">
                        <div class="align-self-center">
                            <div class="heading-big mr-3">{{ player.rank_global }}.</div>
                        </div>
                        <div class="list-user">
                            <img class="avatar-xs mr-2" :src="player.image">
                            <div class="list-names">
                                <div class="heading-mid">{{ player.display_name }}</div>
                                <div class="heading-xs mt-1"><span class="bold">{{ player.range }}</span> godina</div>
                            </div>
                        </div>
                        <div class="action">
                            <div v-html="calculateDiff(player)"></div>
                        </div>
                    </div>
                </router-link>
            </b-tab>
        </b-tabs>

        <b-modal ref="modalAge"
                 title="Dob"
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
    </div>
</template>

<script>
    export default {
        name: "Ranks",
        data() {
            return {
                ages: ['Sve', '0-10', '10-20', '20-30', '30-40', '40-50', '50-60', '60-70', '70-80', '80-90', '90-100'],
                selected_age: ['Sve'],
                club_players: [],
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
            user() {
                return this.$store.state.user;
            },
        },
        methods: {
            calculateDiff(player) {
                let rating1 = this.user.score,
                    rating2 = player.score,
                    r1 = Math.pow(10, rating1/400),
                    r2 = Math.pow(10, rating2/400),
                    e1 = r1 / (r1+r2);
                let win = Math.round( 32 * (1-e1) ), lose = Math.round( 32 * (-e1) );
                return '<span class="score_win">'+ win + '</span><span class="score_lose"> ' + lose + '</span>'
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
            activeAge(age) {
                return this.selected_age.indexOf(age);
            },
            activateAge(age) {
                if (age === 'Sve') {
                    this.selected_age = ['Sve'];
                    return;
                }
                if (this.selected_age.indexOf('Sve')>-1) {
                    this.selected_age = [age];
                    return;
                }
                if (this.activeAge(age)>-1) {
                    this.selected_age.splice(this.activeAge(age), 1);
                    return;
                }
                this.selected_age.push(age);
            },
            getPlayers(modal) {
                this.$store.commit('spinner');
                this.axios.post('players', {age:this.selectedAge, term: this.search_term, offset: this.offset, ranked: true, is_club: true})
                    .then((res) => {
                        if (this.offset !== 0) {
                            if (res.data.players.length) {
                                this.club_players = [...this.club_players, ...res.data.players];
                            }
                            else {
                                this.nomore = true;
                            }
                        }
                        else {
                            this.club_players = res.data.players;

                        }
                        this.total = res.data.total;
                        this.$store.commit('spinner');
                    });
                if (modal) {
                    hideModal(modal);
                }
            },
            bottomVisible() {
                return ((window.innerHeight + window.pageYOffset+200) >= document.body.offsetHeight)
            },

        },
        watch: {
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
            this.$store.dispatch('user/refresh');
        },
    }
</script>