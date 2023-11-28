<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Notifikacije</div>
                </div>
            </div>
        </div>

        <!-- Activity Stream -->
        <div class="activity-container mt-4">
            <div class="text-center mb-3"><button @click.prevent="markAll" class="btn btn-primary markall">Označi sve pročitanim</button></div>

            <!--Admin-only-->
            <!--Prikazuje prvo sve rezervacije terena, onda ostale po vremenu-->
            <router-link :class="{activity: true, unread:!notification.read_at}" :to="notification.data.link" v-if="isAdmin" v-for="notification in admin_notifications" :key="notification.id">
                <div class="activity-item">
                    <div class="activity-user">
                        <img class="avatar-xs align-self-center mr-2" :src="notification.data.user.image">
                        <div class="activity-names">
                            <div class="heading-xs-date text-primary mb-1">{{ notification.data.time }}</div>
                            <div class="heading-mid">{{ notification.data.user.name }}</div>
                            <div class="power-medium bg-danger my-2">{{ notification.data.message }}</div>
                        </div>
                    </div>
                </div>
            </router-link>
            <!--End-->
            <router-link :class="{activity: true, unread:!notification.read_at}" :to="notification.data.link" v-for="notification in notifications" :key="notification.id" @click.native="markAsRead(notification.id)">
                <div class="activity-item">
                    <div class="activity-user">
                        <img class="avatar-xs align-self-center mr-2" :src="notification.data.user.image">
                        <div class="activity-names">
                            <div class="heading-xs-date text-primary mb-1">{{ notification.data.time | moment('DD.MM.Y H:m') }}</div>
                            <div class="heading-mid">{{ notification.data.user.name }}</div>
                            <div class="heading-small-message mt-1">{{ notification.data.message }}</div>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                notifications: [],
                admin_notifications: []
            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            },
            isAdmin() {
                return this.$store.getters['user/isAdmin'];
            },
        },
        methods: {
            markAsRead(id) {
                this.$store.dispatch('notifications/mark', id);
            },
            markAll() {
                this.$store.dispatch('notifications/markAll')
                    .then((res) => {
                        this.notifications = res;
                    })
            }
        },
        mounted() {
            this.axios.get('notifications/all')
                .then((res) => {
                    this.notifications = res.data;
                    this.$store.commit('notifications/update', res.data);
                })
        }
    }
</script>