<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <!--           Podloga boxa se definira jačinom igrača - u ovom slučaju power-70 jer ima 77,43% jačine-->
                <div :class="['profile-box', 'full-width', 'power-'+player.rounded_power]">
                    <div class="text-center d-flex flex-column">
                        <div class="heading text-white text-uppercase mt-3">Profil igrača</div>
                        <img class="avatar-l align-self-center mt-3" v-bind:src="player.image">
                        <div class="heading-profile mt-2 text-white">{{ player.display_name }}</div>
                        <div class="heading-mid-lighter text-uppercase mt-2 text-white">{{ player.range }} godina</div>
                        <div class="heading-big-bigger my-2 text-white">{{ player.score }} bodova</div>
                    </div>
                    <div class="btn-box text-center mt-4">
                        <a class="btn btn-primary btn-call" :href="'tel:' + player.phone" role="button" @click="$ga.event('button', 'phone', user.id + '-' + player.id)">Nazovi</a>
                        <router-link class="btn btn-primary btn-message" :to="'/me/message/new/'+player.id" role="button">Pošalji poruku</router-link>
                    </div>
                </div>

                <div class="btn-box text-center mt-4" v-if="isAdmin && status === 'blocked'">
                    <div class="heading-section full-width text-danger my-3">Korisnik je blokiran</div>
                </div>
                <div class="btn-box text-center mt-4" v-if="isAdmin">
                    <a class="btn btn-warning btn-lg" @click.prevent="showModal('blockUser')" v-if="status !== 'blocked'">Blokiraj korisnika</a>
                    <a class="btn btn-warning btn-lg" @click.prevent="showModal('unblockUser')" v-if="status === 'blocked'">Odblokiraj korisnika</a>
                    <a class="btn btn-danger btn-lg" @click.prevent="showModal('deleteUser')">Izbriši korisnika</a>
                    <a class="btn btn-primary btn-lg" @click.prevent="showModal('make_adminUser')" v-if="status !== 'admin'">Postavi kao administratora</a>
                    <a class="btn btn-primary btn-lg" @click.prevent="showModal('remove_adminUser')" v-if="status === 'admin'">Postavi kao administratora</a>
                </div>

                <div class="heading-section full-width mt-4">Statistika</div>

                <!--Tabs-->
                <b-tabs pills nav-class="justify-content-center mt-3">
                    <b-tab title="Klub" active>
                        <div class="stats mt-4">
                            <div class="stats-box">
                                <div class="stats-item">
                                    <div class="stats-label">Pozicija</div>
                                    <div class="stats-text">{{ player.rank_club }}</div>
                                </div>
                                <div class="stats-item">
                                    <div class="stats-label">Broj mečeva</div>
                                    <div class="stats-text">{{ player.results.length }}</div>
                                </div>
                            </div>
                        </div>
                        <div :class="['power-box', 'power-'+player.rounded_power, 'mt-3']">
                            <div class="power-label">Jakost igrača</div>
                            <div class="power-text">{{ player.power }}%</div>
                        </div>
                    </b-tab>
                    <b-tab title="Ukupno">
                        <div class="stats mt-4">
                            <div class="stats-box">
                                <div class="stats-item">
                                    <div class="stats-label">Pozicija</div>
                                    <div class="stats-text">{{ player.rank_global }}</div>
                                </div>
                                <div class="stats-item">
                                    <div class="stats-label">Broj mečeva</div>
                                    <div class="stats-text">{{ player.results.length }}</div>
                                </div>
                            </div>
                        </div>
                        <div :class="['power-box', 'power-'+player.rounded_power, 'mt-3']">
                            <div class="power-label">Jakost igrača</div>
                            <div class="power-text">{{ player.power }}%</div>
                        </div>
                    </b-tab>
                </b-tabs>
                <!-- Tabs end -->

                <div class="heading-section full-width mt-5">Rezultati</div>
                <!--Tabs-->
                <b-tabs pills nav-class="justify-content-center mt-3">
                    <b-tab title="Rezultati" active>

                       <div class="score-track" v-if="player.id">
                           <div class="">Pobjeda<span class="score_win">{{ player.winslosses.wins }}</span></div>
                           <div class=""><span class="score_lose">{{ player.winslosses.losses }}</span>Poraza</div>
                       </div>

                        <div class="results mt-5" v-if="player.results.length" v-for="result in player.results">
                            <result-box :result="result"></result-box>
                        </div>
                        <div class="text-center mt-4" v-if="!player.results.length">
                            <img class="" src="../../../images/tennis-player.svg">
                            <div class="heading-big-bigger text-uppercase">Ups!</div>
                            <div class="heading-small">Igrač nije odigrao niti jedan meč.</div>
                        </div>
                    </b-tab>
                    <b-tab title="Međusobno" v-if="player.id !== user.id">

                       <!--<div class="score-track">
                           <div class="">Pobjeda<span class="score_win">{{ diff[0] }}</span></div>
                           <div class=""><span class="score_lose">{{ diff[1] }}</span>Poraza</div>
                       </div>-->

                        <div class="results mt-5" v-if="my_results && my_results.length" v-for="result in my_results">
                            <result-box :result="result"></result-box>
                        </div>
                        <div class="text-center mt-4 mb-4" v-if="!my_results.length">
                            <img class="" src="../../../images/tennis-player.svg">
                            <div class="heading-big-bigger text-uppercase">Ups!</div>
                            <div class="heading-small">Niste odigrali niti jedan meč.</div>
                        </div>
                    </b-tab>
                </b-tabs>
                <!-- Tabs end -->
            </div>
        </div>
        <b-modal ref="blockUser"
                 title="Želite blokirati korisnika?"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="Blokiraj"
                 @cancel="updateUser('block')"
        >
            <div class="modal-container">
                <div class="modal-subtitle">
                    Blokirani korisnik može pregledavati stranice ali je onemogućena rezervacija i komunikacija
                </div>
            </div>
        </b-modal>
        <b-modal ref="unblockUser"
                 title="Želite odblokirati korisnika?"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="Odblokiraj"
                 @cancel="updateUser('unblock')"
        >
            <div class="modal-container">
                <div class="modal-subtitle">
                    Korisniku će biti vraćena prava člana u klubu.
                </div>
            </div>
        </b-modal>
        <b-modal ref="make_adminUser"
                 title="Administratorske ovlasti"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="Potvrdi"
                 @cancel="updateUser('make_admin')"
        >
            <div class="modal-container">
                <div class="modal-subtitle">
                    Ako potvrdite, korisnik će imati ovlaštenja kao i Vi
                </div>
            </div>
        </b-modal>
        <b-modal ref="remove_adminUser"
                 title="Administratorske ovlasti"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="Potvrdi"
                 @cancel="updateUser('remove_admin')"
        >
            <div class="modal-container">
                <div class="modal-subtitle">
                    Ako potvrdite, korisnik će ostati samo član u klubu
                </div>
            </div>
        </b-modal>
        <b-modal ref="deleteUser"
                 title="Brisanje korisnika"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="Potvrdi"
                 @cancel="updateUser('delete')"
        >
            <div class="modal-container">
                <div class="modal-subtitle">
                    Ako potvrdite, korisnik više neće biti član kluba, ali profil mu ostaje aktivan.
                </div>
            </div>
        </b-modal>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                player : {
                    results: []
                },
                my_results: []
            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            },
            club() {
                return this.$store.state.club;
            },
            status() {
                if (this.player.id) {
                    return _.filter(this.player.clubs, (club) => {
                        return club.id === this.club.id;
                    })[0].pivot.status;
                }
            },
            isAdmin() {
                return this.$store.getters['user/isAdmin'];
            },
        },
        methods: {
            updateUser(action) {
                if (this.player.id) {
                    this.axios.put('admin/club/player/' + this.player.id, {
                        action: action,
                    })
                        .then((res) => {
                            if (action === 'delete') {
                                this.$router.push('/club/players');
                            }
                            else {
                                this.player.clubs[0].pivot.status = res.data;
                                this.hideModal(action+'User');
                            }
                        });

                }
            },
            showModal(modal) {
                this.$refs[modal].show();
            },
            hideModal(modal) {
                this.$refs[modal].hide();
            }
        },
        mounted() {
            let id;
            if (this.$route.params.id) {
                id = this.$route.params.id;
            }
            else {
                id = this.user.id;
            }
            this.axios.get('player/'+id)
                .then((res) => {
                    this.player = res.data;
                })
                .catch(() => {
                    this.$router.push('/');
                });
            if (id !== this.user.id) {
                this.axios.get('player/'+id+'/me')
                    .then((res) => {
                        this.my_results = res.data.results;
                    })
            }
        }
    }
</script>