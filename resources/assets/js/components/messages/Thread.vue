<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Pošalji poruku</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col mb-6">
                <template v-if="thread.players.length === 2">
                <router-link :to="'/player/'+player.id" v-if="player">
                    <div class="message-single-container mt-2 mb-1">
                        <div class="message-single">
                            <div class="message-single-item">
                                <div class="message-single-user">
                                    <img class="avatar-xs mr-2" :src="player.image">
                                    <div class="message-single-names">
                                        <div class="heading-mid">{{ player.display_name }}</div>
                                        <div class="heading-xs mt-1"><span class="bold">{{ player.range }}</span> godina</div>
                                    </div>
                                </div>
                                <div class="action">
                                    <div :class="['power-medium', 'power-'+player.rounded_power]">{{ player.rounded_power }}%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </router-link>
                </template>
                <template v-else>
                    <div class="message-multi-container mt-2">
                        <div class="heading-mid-dialog">
                            <span class="d-inline-block mb-2">{{ thread.players.length }} igrača</span>
                        </div>
                        <div class="message-multi-players">
                            <img class="avatar-xs" :src="player.image" v-for="player in thread.players">
                        </div>
                    </div>
                </template>

                <div class="loadmore-box">
                    <button class="btn btn-primary" @click="loadMore" v-if="thread.messages.length < thread.total">Učitaj starije</button>
                </div>
                <div class="comments-item mt-2" v-for="message in messages">
                   <img class="avatar-xs mr-2" :src="message.player.image">
                    <div class="comment-box">
                        <div class="comment-author">{{ message.player.display_name }}</div>
                        <div class="comment-text">{{ message.message }}
                            <img v-if="message.multimedia_type==='image'" :src="message.multimedia">
                        </div>
                        <div class="comment-date">{{ message.created_at | moment('DD.MM. HH:mm') }}</div>
                    </div>

                </div>

                <div class="comment-input">
                     <div class="form-group">
                        <input type="text" class="form-control" id="comment" placeholder="Upiši poruku" v-model="new_message">
                         <croppa ref="messageImage" v-if="isAdmin"
                                 :prevent-white-space="true"
                                 :show-loading="true"
                                 :replace-drop="true"
                                 :auto-sizing="true"
                         ></croppa>
                    </div>
                    <div class="comment-send" @click.prevent="submitMessage"></div>
                    <div class="add-image" v-if="isAdmin" @click="addImage">Slika</div>
                </div>

                <div class="form-radio" v-if="false">
                    <legend class=" mt-4 mb-2">Predloženi datumi</legend>
                    <div class="heading-bullet">Svejedno, 18.09., 19.09., 20.09.</div>
                    <legend class=" mt-4 mb-2">Predloženo vrijeme</legend>
                    <div class="heading-bullet">Svejedno, 20:00, 21:00, 22:30</div>
                </div>
                <div class="form-radio" v-if="false">
                    <legend class="mt-3">Dodatne opcije</legend>
                    <input type="checkbox" id="court-bu" name="availability" value="yes" checked readonly="true">
                    <input type="checkbox" id="court-buy" name="availability" value="yes" checked readonly="true">
                    <label for="court-buy">Plaćam teren</label>
                    <input type="checkbox" id="drink-buy" name="availability" value="no" checked>
                    <label for="drink-buy">Plaćam piće</label>
                    <input type="checkbox" id="new-balls" name="availability" value="no" checked>
                    <label for="new-balls">Donosim nove loptice</label>
                </div>


                <div class="text-center btn-box mt-4 mb-5" v-if="false">
                    <a class="btn btn-danger" href="#" role="button" data-toggle="modal"  data-target="#modal-message-reject">Zahvali se</a>
                    <a class="btn btn-primary" href="#" role="button" data-toggle="modal"  data-target="#modal-message-accept">Prihvati</a>
                </div>



            </div>
        </div>


    </div>
</template>

<script>
    export default {
        name: "Thread",
        data() {
            return {
                thread: {
                    messages: [],
                    players: []
                },
                new_message: '',
                sending: false,
                load_more: true,
                showImage: false
            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            },
            player() {
                return _.find(this.thread.players, (player) => {
                    return player.id !== this.user.id;
                })
            },
            messages() {
                return this.thread.messages.slice().reverse();
            },
            isAdmin() {
                return this.$store.getters['user/isAdmin'];
            },
        },
        methods: {
            loadMore() {
                if (this.load_more) {
                    this.axios.post('thread/' + this.$route.params.id, {offset: this.thread.messages.length})
                        .then((res) => {
                            if (res.data.length) {
                                this.thread.messages = [...this.thread.messages, ...res.data];
                            }
                            else {
                                this.load_more = false;
                            }
                        });
                }
            },
            submitMessage() {
                if (!this.sending) {
                    this.sending = true;
                    if (this.$refs.messageImage.chosenFile) {
                        this.$refs.messageImage.generateBlob(
                            blob => {
                                let data = new FormData();
                                data.append('image', blob);
                                if (this.new_message) {
                                    data.append('message', this.new_message);
                                }
                                this.axios.post('thread/' + this.$route.params.id + '/message', data)
                                    .then((res) => {
                                        this.new_message = '';
                                        this.sending = false;
                                    })
                            },
                            'image/jpeg'
                        );
                        return;
                    }
                    if (this.new_message) {
                        this.axios.post('thread/' + this.$route.params.id + '/message', {message: this.new_message})
                            .then((res) => {
                                this.new_message = '';
                                this.sending = false;
                            })
                    }
                }
            },
            addImage() {
                this.showImage = true;
                this.$refs.messageImage.chooseFile();
            }
        },
        mounted() {
            this.axios.get('thread/' + this.$route.params.id)
                .then((res) => {
                    this.thread = res.data;
                    this.$echo.channel('thread.' + this.thread.id)
                        .listen('MessageSent', (e) => {
                            this.thread.messages.unshift(e.message);
                            this.thread.total++;
                        });
                    setTimeout(() => {
                        window.scrollTo(0,document.body.scrollHeight);
                    }, 500);

                })
                .catch(() => {
                    this.$router.push('/me/messages');
                })
        }
    }
</script>

<style scoped>

</style>