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
                <div class="btn-box text-center mt-4">
                    <router-link class="btn btn-primary btn-lg" to="/club/results/new" role="button" v-if="user.isLoggedIn">Upiši rezultat</router-link>
                </div>

                <b-tabs pills nav-class="justify-content-center mt-3">
                    <b-tab title="Svi rezultati" active>
                        <div class="results mt-5">
                            <div class="filter">
                                <div class="filter-box">
                                    <div class="filter-item">
                                        <div class="filter-label">Tip meča</div>
                                        <div class="form-group">
                                            <input type="text" class="form-control bg-white" @click.prevent="showModal('modalMatch')" readonly="true" :placeholder="options[selected_match]">
                                            <i class="fa fa-caret-down icon-caret" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="summary-display">Ukupno rezultata <span>{{ total }}</span> </div>

                            <result-box :result="result" v-for="result in all_results" :key="result.id" :verify="true" :delete="true" :dispute="true" v-on:hideModal="hideModal" v-on:showModal="showModal"></result-box>
                        </div>
                    </b-tab>
                    <b-tab title="Moji rezultati" v-if="user.isLoggedIn">
                        <div class="results mt-5">
                            <div class="score-track">
                                <div class="">Pobjeda<span class="score_win">???</span></div>
                                <div class=""><span class="score_lose">???</span>Poraza</div>
                            </div>
                            <result-box :result="result" v-for="result in my_results" :key="result.id" :verify="true" :delete="true" :dispute="true" v-on:hideModal="hideModal" v-on:showModal="showModal"></result-box>
                        </div>
                    </b-tab>
                </b-tabs>
            </div>
        </div>
        <b-modal ref="modalMatch"
                     title="Podloga"
                     @ok="hideModal('modalMatch')"
                     ok-variant="danger"
                     ok-title="Zatvori"
                     @cancel="getPlayers"
                     cancel-variant="success"
                     cancel-title="U redu">
                <div class="modal-container">
                    <div v-for="(option, key) in options" v-bind:class="['modal-element-white', 'modal-text', selected_match===key?'active':'']" v-on:click="selected_match = key">{{ option }}</div>
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
            <div class="modal-subtitle">Vaš partner će dobiti obavijest o rezultatu meča. Nakon verifikacije rezultat će biti prikazan, a vaši bodovi osvježeni.</div>
        </b-modal>
        <b-modal class="modal_vertical_center" ref="modalDelete"
                 title="Obriši rezultat"
                 ok-title="Otkaži"
                 ok-variant="danger"
                 cancel-title="U redu"
                 cancel-variant="success"
                 @ok="hideModal('modalDelete')"
                 @cancel="deleteResult"
        >
            <div class="modal-subtitle">Želite obrisati ovaj rezultat. Ova radnja se ne može poništiti.</div>
        </b-modal>
        <b-modal class="modal_vertical_center" ref="modalDispute"
                 title="Obriši rezultat"
                 ok-title="Otkaži"
                 ok-variant="danger"
                 cancel-title="U redu"
                 cancel-variant="success"
                 @cancel="disputeResult"
        >
            <div class="modal-subtitle">Želite osporiti ovaj rezultat. Ova radnja se ne može poništiti.</div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        name: "Results",
        data() {
            return {
                all_results: [],
                my_results: [],
                selected_match: 'all',
                options: {
                    'all': 'Svi',
                    'official': 'Službeni meč',
                    'friendly': 'Prijateljski meč'
                },
                offset: 0,
                bottom: false,
                total: 0,
                nomore: false,
                result_id: 0
            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            }
        },
        methods: {
            getPlayers() {
                this.$store.commit('spinner');
                this.axios.get('club/results/'+this.selected_match+'/'+this.offset)
                    .then((res) => {
                        if (this.offset !== 0) {
                            if (res.data.results.length) {
                                this.all_results = [...this.all_results, ...res.data.results];
                            }
                            else {
                                this.nomore = true;
                            }
                        }
                        else {
                            this.all_results = res.data.results;

                        }
                        this.total = res.data.total;
                        this.$store.commit('spinner');
                        this.hideModal('modalMatch');
                    });
            },
            showModal(modal, result_id) {
                this.$refs[modal].show();
                if (result_id) {
                    this.result_id = result_id;
                }
            },
            hideModal(modal) {
                this.$refs[modal].hide();
            },
            verifyResult() {
                if (this.result_id) {
                    this.axios.post('club/result/' + this.result_id + '/verify')
                        .then((res) => {
                            _.each(this.all_results, (item) => {
                                if (item.id === this.result_id) {
                                    item.verified = true;
                                }
                            });
                            this.hideModal('modalVerify');
                        });
                }
            },
            deleteResult(id) {
                if (this.result_id) {
                    this.hideModal('modalDelete');

                    this.axios.delete('club/result/' + this.result_id)
                        .then(() => {
                            this.all_results = _.filter(this.all_results, (item) => {
                                return item.id !== this.result_id;
                            });
                            this.result_id = 0;
                        });
                }
            },
            disputeResult(id) {
                if (this.result_id) {
                    this.hideModal('modalDispute');
                    this.axios.delete('club/result/' + this.result_id + '/dispute')
                        .then(() => {
                            this.$router.push('/club/results');
                        });
                }
            },
            bottomVisible() {
                return ((window.innerHeight + window.pageYOffset+200) >= document.body.offsetHeight)
            },
        },
        watch: {
            bottom(bottom) {
                if (bottom && !this.nomore) {
                    this.offset +=10;
                    this.getPlayers();
                }
            },
            selected_match() {
                this.all_results = [];
                this.bottom = false;
                this.nomore = false;
                this.offset = 0;
                this.getPlayers();
            }
        },
        mounted() {
            window.addEventListener('scroll', () => {
                this.bottom = this.bottomVisible()
            });
            this.offset = 0;
            this.getPlayers();
            if (this.user.isLoggedIn) {
                this.axios.get('me/results')
                    .then((res) => {
                        this.my_results = res.data.results;
                    });
            }
        }
    }
</script>

<style scoped>

</style>