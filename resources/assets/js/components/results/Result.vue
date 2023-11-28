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
                <div class="results mt-5">
                    <result-box v-if="result" :result="result" :verify="true" :delete="true" :dispute="true" v-on:hideModal="hideModal" v-on:showModal="showModal"></result-box>
                </div>
            </div>
        </div>
        <div class="comments">
            <div class="comments-title">Komentari</div>
            <div class="comments-item" v-for="comment in result.comments" :ket="comment.id">
                <router-link :to="'/player/'+comment.player.id"><img class="avatar-xs mr-2" :src="comment.player.image"></router-link>
                <div class="comment-box">
                    <div class="comment-author">{{ comment.player.display_name }}</div>
                    <div class="comment-text">{{ comment.message }}</div>
                    <div class="comment-date">{{ comment.created_at | moment('HH:mm') }}</div>
                </div>
            </div>
            <div class="comment-input">
<!--                 <div class="comment-background"></div>-->
                 <div class="form-group">
                    <input type="text" class="form-control" id="comment" placeholder="Upiši poruku" v-model="comment" :disabled="sending">
                </div>
                <div :class="['comment-send', sending?'disabled':'']" @click.prevent="submitComment"></div>
            </div>
        </div>
        <b-modal ref="modalVerify"
                 @ok="hideModal('modalVerify')"
                 title="Verifikacija meča"
                 ok-variant="danger"
                 ok-title="Otkaži"
                 @cancel="verifyResult"
                 cancel-variant="success"
                 cancel-title="U redu">
            <div class="modal-subtitle">Vaš partner će dobiti obavijest o rezultatu meča. Nakon verifikacije rezultat će biti prikazan, a vaši bodovi osvježeni.</div>
        </b-modal>
        <b-modal ref="modalDelete"
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
        <b-modal ref="modalDispute"
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
        name: "Result",
        data() {
            return {
                result: {
                    players:[],
                    non_member: true,
                    comments: []
                },
                comment: '',
                sending: false
            }
        },
        methods: {
            showModal(modal) {
                this.$refs[modal].show();
            },
            hideModal(modal) {
                this.$refs[modal].hide();
            },
            verifyResult() {
                this.axios.post('club/result/'+this.result.id+'/verify')
                    .then((res) => {
                        this.result = res.data.result;
                        this.hideModal('modalVerify');
                    });
            },
            deleteResult() {
                this.hideModal('modalDelete');
                this.axios.delete('club/result/'+this.result.id)
                    .then(() => {
                        this.$router.push('/club/results');
                    });
            },
            disputeResult() {
                this.hideModal('modalDispute');
                this.axios.delete('club/result/'+this.result.id+'/dispute')
                    .then(() => {
                        this.$router.push('/club/results');
                    });
            },
            submitComment() {
                if (this.comment && !this.sending) {
                    this.sending = true;
                    this.axios.post('club/result/' + this.result.id + '/comment', {message: this.comment})
                        .then((res) => {
                            this.comment = '';
                            this.sending = false;
                        }).catch(() => {
                            this.sending = false;
                    })
                }
            }
        },
        mounted() {
            this.axios.get('club/result/'+this.$route.params.id)
                .then((res) => {
                    this.result = res.data.result;
                    this.$echo.channel('result.' + this.result.id)
                        .listen('ResultCommentAdded', (e) => {
                            this.result.comments.push(e.comment);
                        });
                });

        },
        computed: {
        }
    }
</script>