<template>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="navbar-header">
            <!-- Collapsed Hamburger -->
            <img class="hamburger" src="../../../images/hamburger.svg" v-on:click="showModal('modal_menu')" width="30">

            <!-- Branding Image -->
            <router-link class="navbar-brand" to="/">{{ club.name }}</router-link>
            <!-- User/notification Image -->
            <div class="user" v-if="isLoggedIn">
                <img :src="user.image" width="30" v-on:click="showModal('modal_menu_user')" class="avatar-xs">
                <span class="tag bg-primary">{{ notifications }}</span>
            </div>
            <div class="user" v-else>
                <router-link to="/login">Prijava</router-link>
            </div>
        </div>

        <b-modal ref="modal_menu_user"
             ok-only
             @ok="hideModal('modal_menu_user')"
             ok-variant="danger"
             ok-title="Zatvori"
             :title="user.first_name"
             title-tag="div">
        <div class="modal-container user-menu">
            <router-link to="/me/notifications" class="modal-element-left modal-text" @click.native="hideModal('modal_menu_user')">
                <span class="badge badge-primary mr-2 modal_notification_icon">{{ notifications }}</span>Obavijesti
            </router-link>
            <router-link to="/me/messages" class="modal-element-left modal-text" @click.native="hideModal('modal_menu_user')">
                <img class="mr-2" src="../../../images/messages_user.svg">Poruke</router-link>
<!--
            <router-link to="/me/challengers" class="modal-element-left modal-text" @click.native="hideModal('modal_menu_user')">
                <img class="mr-2" src="../../../images/duel.svg" width="17">Izazivači
            </router-link>
-->
            <router-link to="/me" class="modal-element-left modal-text" @click.native="hideModal('modal_menu_user')">
                <img class="mr-2 black-white" src="../../../images/profile-user.svg">Moj profil
            </router-link>
            <router-link to="/me/profile" class="modal-element-left modal-text" @click.native="hideModal('modal_menu_user')">
                <img class="mr-2" src="../../../images/my_data.svg">Moji podaci
            </router-link>
            <router-link to="/me/reservations" class="modal-element-left modal-text" @click.native="hideModal('modal_menu_user')">
                <img class="mr-2" src="../../../images/my_reservation.svg">Moje rezervacije
            </router-link>
            <router-link v-if="isAdmin" to="/admin" class="modal-element-left modal-text" @click.native="hideModal('modal_menu_user')">
                <img class="mr-2" src="../../../images/admin.svg">Ja Admin
            </router-link>
            <a v-if="isLoggedIn" v-on:click="logout" href="#" class="modal-element-left modal-text">
                <img class="mr-2" src="../../../images/logout.svg">Odjavi se
            </a>
        </div>
    </b-modal>
        <b-modal ref="modal_menu"
             ok-only
             title="Izbornik"
             @ok="hideModal('modal_menu')"
             ok-variant="danger"
             ok-title="Zatvori"
             title-tag="div">
        <div class="modal-container">
            <router-link to="/" class="modal-element-left modal-text icon-home" @click.native="hideModal('modal_menu')">Početna</router-link>
            <router-link to="/club/courts" class="modal-element-left modal-text icon-reserve" @click.native="hideModal('modal_menu')">Rezerviraj teren</router-link>
            <router-link to="/club/players" class="modal-element-left modal-text icon-players" @click.native="hideModal('modal_menu')">Igrači</router-link>
            <router-link to="/club/results" class="modal-element-left modal-text icon-results" @click.native="hideModal('modal_menu')">Rezultati</router-link>
            <router-link to="/club/ranks" class="modal-element-left modal-text icon-ranks" @click.native="hideModal('modal_menu')">Rang ljestvice</router-link>
            <router-link to="/club/news" class="modal-element-left modal-text icon-news" @click.native="hideModal('modal_menu')">Novosti</router-link>
            <router-link to="/club/about" class="modal-element-left modal-text icon-about" @click.native="hideModal('modal_menu')">O klubu</router-link>
        </div>
    </b-modal>
    </nav>
</template>
<script>
    export default {
        data: function () {
            return {}
        },
        computed: {
            isLoggedIn() {
                return this.user.isLoggedIn;
            },
            user() {
                return this.$store.state.user;
            },
            isAdmin() {
                return this.$store.getters['user/isAdmin'];
            },
            club() {
                return this.$deepModel('club');
            },
            notifications() {
                return this.$store.getters['notifications/number'];
            }
        },
        methods: {
            showModal(modal) {
                this.$refs[modal].show();
            },
            hideModal(modal) {
                this.$refs[modal].hide();
            },
            logout() {
                this.$refs.modal_menu_user.hide();
                this.$store.dispatch('user/logout');
                this.$router.push('/');
            }
        },
        mounted() {
            this.$store.dispatch('notifications/init', this);
        }
    }
</script>