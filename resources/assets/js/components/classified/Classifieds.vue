<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Oglasnik</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="btn-box text-center mt-4">
                    <router-link class="btn btn-primary btn-lg" to="/classified/new" role="button" v-if="user.isLoggedIn">Objavi oglas</router-link>
                </div>

                <b-tabs pills nav-class="justify-content-center mt-3">
                    <b-tab title="Svi oglasi" active>
                        <div class="results mt-5">
                            <div class="filter">
                                <div class="filter-box">
                                    <div class="filter-item">
                                        <div class="filter-label">Kategorija</div>
                                        <div class="form-group">
                                            <input type="text" class="form-control bg-white" @click.prevent="showModal('modalMatch')" readonly="true" :placeholder="options[selected_category]">
                                            <i class="fa fa-caret-down icon-caret" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="summary-display">Ukupno oglasa <span>{{ total }}</span> </div>

                           <router-link :to="'/classified/'+classified.id" class="classified" v-for="classified in classifieds" :key="classified.id">
                                <img class="classified_img" :src="classified.image" alt="">
                                <div class="classified_box">
                                    <div class="article_date">{{ classified.created_at | moment('DD.MM.YYYY.') }}</div>
                                    <div class="article_title">{{ classified.title }}</div>
                                    <div class="article_price">{{ classified.price }} kn</div>
                                </div>
                            </router-link>
                        </div>
                    </b-tab>
                    <b-tab title="Moji oglasi" v-if="user.isLoggedIn">
                        <div class="results mt-5">
                            <result-box :result="result" v-for="result in my_classifieds" :key="result.id" :verify="true" :delete="true" :dispute="true" v-on:hideModal="hideModal" v-on:showModal="showModal"></result-box>
                        </div>
                    </b-tab>
                </b-tabs>
            </div>
        </div>
        <b-modal ref="modalMatch"
                     title="Kategorije"
                     @ok="hideModal('modalMatch')"
                     ok-variant="danger"
                     ok-title="Zatvori"
                     @cancel="getClassifieds"
                     cancel-variant="success"
                     cancel-title="U redu">
                <div class="modal-container">
                    <div v-for="(option, key) in options" v-bind:class="['modal-element-white', 'modal-text', selected_category===key?'active':'']" v-on:click="selected_category = key">{{ option }}</div>
                </div>
            </b-modal>
    </div>
</template>

<script>
    export default {
        name: "Results",
        data() {
            return {
                classifieds: [],
                my_classifieds: [],
                selected_category: 'all',
                options: {
                    'all': 'Sve',
                    'racket': 'Reketi',
                    'bags': 'Torbe',
                    'other': 'Ostala oprema'
                },
                offset: 0,
                bottom: false,
                total: 0
            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            }
        },
        methods: {
            getClassifieds() {
                this.axios.post('classifieds', {category: this.selected_category, offset: this.offset})
                    .then((res) => {
                        if (this.offset !== 0) {
                            this.classifieds = [...this.classifieds, ...res.data.classifieds];
                        }
                        else {
                            this.classifieds = res.data.classifieds;
                        }
                        this.total = res.data.total;
                        this.hideModal('modalMatch');
                    });
            },
            showModal(modal) {
                this.$refs[modal].show();
            },
            hideModal(modal) {
                this.$refs[modal].hide();
            },
            bottomVisible() {
                return ((window.innerHeight + window.pageYOffset+200) >= document.body.offsetHeight)
            },
        },
        watch: {
            bottom(bottom) {
                if (bottom) {
                    this.offset +=20;
                    this.getClassifieds();
                }
            }
        },
        created() {
            window.addEventListener('scroll', () => {
                this.bottom = this.bottomVisible()
            });
            this.offset = 0;
            this.getClassifieds();
            if (this.user.isLoggedIn) {

            }
        }
    }
</script>

<style scoped>

</style>