<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Korisnici</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">

                <div class="search-bar mt-4">
                    <input class="form-control" type="search" placeholder="Pronađi igrača" id="example-search-input" v-model="search_term">
                    <i class="fa fa-search icon-search" aria-hidden="true"></i>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <!-- Table list no checkbox -->
        <div class="row">
            <div class="col">
                <div class="list-container mt-2 mb-4">
                    <div class="list" v-for="player in members">
                        <div class="list-item">
                            <div class="heading-num-small align-self-center">
                            </div>
                            <router-link :to="'/player/'+player.id" class="list-user">
                                <img class="avatar-xs mr-2" v-bind:src="player.image">
                                <div class="list-names">
                                    <div class="heading-mid">{{player.display_name}}</div>
                                    <div class="heading-xs mt-1"><span class="bold">{{ player.range }}</span> godina</div>
                                </div>
                            </router-link>
                            <div class="action" @click="showModal('userActions', player)">
                                <div class="power-medium bg-primary">{{ status(player) | parseStatus }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <b-modal ref="userActions"
             title="Odobri korisnika"
             ok-variant="danger"
             cancel-variant="success"
             ok-title="Zatvori"
             cancel-title="U redu"
    >
        <div class="modal-container">
            <div class="row">
                <div class="col">
                    <div class="list-container mt-2 mb-4">
                        <div class="list">
                            <div class="list-item" v-if="selected_player">
                                <div class="heading-num-small align-self-center">
                                </div>
                                <router-link :to="'/player/'+selected_player.id" class="list-user">
                                    <img class="avatar-xs mr-2" v-bind:src="selected_player.image">
                                    <div class="list-names">
                                        <div class="heading-mid">{{selected_player.display_name}}</div>
                                        <div class="heading-xs mt-1"><span class="bold">{{ selected_player.range }}</span> godina</div>
                                    </div>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-element-white" v-if="status(selected_player)!=='admin'" @click="updateUser('make_admin')">Postavi za Admina</div>
            <div class="modal-element-white" v-if="status(selected_player)==='admin'" @click="updateUser('remove_admin')">Ukloni Admina</div>
            <div class="modal-element-white" v-if="status(selected_player)==='applicant'" @click="updateUser('approve')">Odobri člana</div>
            <div class="modal-element-white" @click="updateUser('delete')">Izbaci člana</div>
            <div class="modal-element-white" @click="updateUser('block')">Blokiraj člana</div>
            <div class="modal-element-white" @click="updateUser('unblock')">Odblokiraj člana</div>
        </div>
    </b-modal>

    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                members: [],
                selected_player: null,
                search_term: ''
            }
        },
        computed: {

            club() {
                return this.$store.state.club;
            },
            isAdmin() {
                return this.$store.getters['user/isAdmin'];
            },
        },
        filters: {
            parseStatus(value) {
                switch (value) {
                    case 'admin':
                        return 'Admin';
                    case 'member':
                        return 'Član';
                    case 'blocked':
                        return 'Blokiran';
                    case 'applicant':
                        return 'Novi korisnik';
                    default:
                        return '';
                }
            }
        },
        methods: {
            status(player) {
                if (player) {
                    return _.filter(player.clubs, (club) => {
                        return club.id === this.club.id;
                    })[0].pivot.status;
                }
            },
            showModal(modal, player) {
                this.$refs[modal].show();
                this.selected_player = player;
            },
            updateUser(action) {
                if (this.selected_player) {
                    this.axios.put('admin/club/player/' + this.selected_player.id, {
                        action: action,
                    })
                        .then((res) => {
                            if (action === 'delete') {
                                this.members = _.filter(this.members, (member) => {
                                    return member.id !== this.selected_player.id;
                                });
                                this.hideModal('userActions');
                            }
                            else {
                                this.selected_player.clubs[0].pivot.status = res.data;
                                this.hideModal('userActions');
                            }
                        });

                }
            },
            hideModal(modal) {
                this.$refs[modal].hide();
                this.selected_player = null;
            }
        },
        mounted() {
            this.axios.get('admin/club/players/members')
                .then((res) => {
                    this.members = res.data;
                });
        },
        beforeMount() {
            if (!this.isAdmin) {
                this.$router.push('/');
            }
        }
    }
</script>