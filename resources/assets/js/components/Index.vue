<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col nopadding">
                <div class="brand-image">
                    <div class="gradient-200"></div>
                    <img class="brand-logo" :src="club.logo" alt="">
                    <div class="heading-image">Dobrodošli</div>
                </div>
               <div class="severe-weather" v-if="club.weather">Prekid igre na zbog atmosferskih neprilika</div>
            </div>
        </div>
        <div class="row">
            <div class="col nopadding">
                <div class="main-navigation">
                    <router-link to="/club/courts" class="col-4 main-navigation-module">
                        <img src="../../images/reservation.svg">
                        <div class="heading-small text-center mt-2">Rezervacija terena</div>
                    </router-link>
                    <router-link to="/club/players" class="col-4 main-navigation-module">
                        <img src="../../images/players.svg">
                        <div class="heading-small text-center mt-2">Igrači</div>
                    </router-link>
                    <router-link to="/club/results" class="col-4 main-navigation-module">
                        <img src="../../images/results.svg">
                        <div class="heading-small text-center mt-2">Rezultati</div>
                    </router-link>
                    <router-link to="/club/ranks" class="col-4 main-navigation-module">
                        <img src="../../images/top-list.svg">
                        <div class="heading-small text-center mt-2">Rang lista</div>
                    </router-link>
                    <router-link to="/club/schedule" class="col-4 main-navigation-module">
                        <img src="../../images/static/schedule.svg">
                        <div class="heading-small text-center mt-2">Raspored mečeva</div>
                    </router-link>
                    <router-link to="/club/classifieds" class="col-4 main-navigation-module">
                        <img src="../../images/static/shop.svg">
                        <div class="heading-small text-center mt-2">Oglasnik</div>
                    </router-link>

                    <div class="main-navigation-separator"></div>

                    <router-link to="/club/news" class="col-4 main-navigation-module">
                        <img src="../../images/news.svg">
                        <div class="heading-small text-center mt-2">Novosti</div>
                    </router-link>
                    <router-link :to="'/thread/'+club.main_thread" class="col-4 main-navigation-module border-right">
                        <img src="../../images/chat.svg">
                        <div class="heading-small text-center mt-2">Klupski chat</div>
                    </router-link>
                    <router-link to="/me/messages" class="col-4 main-navigation-module">
                        <img src="../../images/messages.svg">
                        <div class="heading-small text-center mt-2">Poruke</div>
                    </router-link>
                    <div class="main-navigation-separator"></div>

                    <div class="main-navigation-separator-big">Svi klubovi</div>

                    <router-link to="/clubs" class="col-4 main-navigation-module soon">
                        <img class="opacity-2" src="../../images/all_clubs.svg">
                        <div class="opacity-2 heading-small text-center mt-2">Popis klubova</div>
                    </router-link>
                    <router-link to="/players" class="col-4 main-navigation-module">
                        <img src="../../images/all_players.svg">
                        <div class="heading-small text-center mt-2">Svi igrači</div>
                    </router-link>
                    <router-link to="/results" class="col-4 main-navigation-module soon">
                        <img class="opacity-2" src="../../images/all_results.svg">
                        <div class="opacity-2 heading-small text-center mt-2">Svi rezultati</div>
                    </router-link>
                    <router-link to="/ranks" class="col-4 main-navigation-module soon">
                        <img class="opacity-2" src="../../images/all_ranks.svg">
                        <div class="opacity-2 heading-small text-center mt-2">Ukupna rang lista</div>
                    </router-link>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {}
        },
        computed: {
            club() {
                return this.$store.state.club;
            }
        },
        methods: {},
        mounted() {
        }
    }
</script>