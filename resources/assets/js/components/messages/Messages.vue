<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Poruke</div>
                </div>
            </div>
        </div>
        <div class="row" v-if="checked.length">
            <div class="col">

                <!--     Ovo je verzija kada korisnik označi poruke-->
                <div class="select-dialog mt-3">
                    <div class="heading-mid-dialog">Označeno <span class="">{{ checked.length }} poruka</span>
                    </div>
                    <div class="text-center">
                        <a class="btn btn-info mt-2" role="button" @click.prevent="deleteMessages">Obriši označeno</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <router-link class="btn btn-primary" to="/thread/new">Nova poruka</router-link>
            </div>
        </div>
        <!-- Table list -->
        <div class="row">
            <div class="col">
                <div class="list-container mt-4 mb-4">
                    <div class="list" v-for="thread in threads" :key="thread.id">
                        <div class="list-item">
                            <div class="heading-num-small align-self-center">
                                <div class="checkbox">
                                    <input type="checkbox" :id="'player-check-'+thread.id" name="player-check" :value="thread.id" v-model="checked" >
                                    <label :for="'player-check-'+thread.id"></label>
                                </div>
                            </div>
                            <router-link :to="'/me/message/'+thread.id" class="list-user" v-if="thread.lastMessage">
                                <img class="avatar-xs align-self-center mr-2" :src="thread.lastMessage.player.image">
                                <div class="list-names">
                                    <div class="heading-xs-date text-primary mb-1">{{ thread.lastMessage.created_at | moment('D.M.Y.') }}</div>
                                    <div class="heading-mid">{{ thread.lastMessage.player.display_name }}</div>
                                    <div class="heading-small-message mt-1">{{ thread.lastMessage.message }}</div>
                                </div>
                            </router-link>
                            <router-link :to="'/me/message/'+thread.id" class="list-user" v-else>
                                <img class="avatar-xs align-self-center mr-2" :src="thread.players[1].image">
                                <div class="list-names">
                                    <div class="heading-xs-date text-primary mb-1">{{ thread.created_at | moment('D.M.Y.') }}</div>
                                    <div class="heading-mid">{{ thread.players[1].display_name }}</div>
                                    <div class="heading-small-message mt-1"></div>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                checked: [],
                threads: []
            }
        },
        methods: {
            deleteMessages() {
                _.forEach(this.checked, (id) => {
                    this.axios.delete('thread/'+id)
                        .then(() => {
                            this.threads = _.filter(this.threads, (t) => {
                                return t.id !== id;
                            });
                        });
                });
                this.checked = [];
            },
            getThreads() {
                this.axios.get('threads')
                    .then((res) => {
                        this.threads = res.data;
                    })
            }
        },
        mounted() {
            this.getThreads();
        }
    }
</script>