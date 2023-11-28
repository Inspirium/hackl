<template>

<div class="container-fluid">
    <div class="row">
        <div class="col nopadding">
            <div class="brand-image">
                <div class="gradient-200"></div>
                <img class="brand-logo" :src="club.logo" alt="">
                <div class="heading-image">Administracija</div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col nopadding">
            <div class="main-navigation">
                <router-link to="/admin/court" class="col-4 main-navigation-module">
                    <img src="../../../images/reservation.svg">
                    <div class="heading-small text-center mt-2">Dodaj teren</div>
                </router-link>
                <router-link to="/admin/players" class="col-4 main-navigation-module">
                    <img src="../../../images/players.svg">
                   <div class="heading-small text-center mt-2">Članovi</div>
                </router-link>
                <router-link to="/admin/club" class="col-4 main-navigation-module">
                    <img src="../../../images/all_clubs.svg">
                    <div class="heading-small text-center mt-2">Profil kluba</div>
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
        beforeMount() {
            if (!this.$store.getters['user/isAdmin']) {
                this.$router.push('/');
            }
        }
    }
</script>