<template>
    <div class="container" v-if="classified">
        <div class="article-single">
            <div class="article_date">{{ classified.created_at | moment('DD.MM.YYYY.') }}</div>
            <div class="article_title">{{ classified.title }}</div>
            <img :src="classified.image" alt="">
            <p class="article_price">Cijena: {{ classified.price }} kn</p>
            <p>{{ classified.description }}</p>
            <!-- player data -->
            <div class="list-container mt-2 mb-4">
                <router-link class="list" :to="'/player/'+classified.user.id">
                    <div class="list-item">
                        <div class="list-user">
                            <img class="avatar-xs mr-2" :src="classified.user.image">
                            <div class="list-names">
                                <div class="heading-mid">{{ classified.user.display_name }}</div>
                            </div>
                        </div>
                    </div>
                </router-link>
            </div>
            <!-- !player data -->
            <div class="btn-box text-center my-4">
                <a class="btn btn-primary btn-call" :href="'tel:' + classified.user.phone" role="button" @click="$ga.event('button', 'phone', $store.state.user.id + '-' + classified.user.id)" v-if="$store.state.user.id !== classified.user.id">Nazovi</a>
                <router-link class="btn btn-primary btn-message" :to="'/me/message/new/'+classified.user.id" role="button" v-if="$store.state.user.id !== classified.user.id">Pošalji poruku</router-link>
                <a class="btn btn-danger" v-if="$store.state.user.id === classified.user.id" @click.prevent="deleteClassified">Obriši oglas</a>
                <router-link :to="'/classified/'+classified.id+'/edit'" class="btn btn-primary" v-if="$store.state.user.id === classified.user.id">Uredi oglas</router-link>
            </div>
        </div>
        <div class="comments">
            <div class="comments-title">Komentari</div>
            <div class="comments-item" v-for="comment in classified.comments" :ket="comment.id">
                <img class="avatar-xs mr-2" :src="comment.player.image">
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
    </div>
</template>

<script>
    export default {
        name: "Classified",
        data() {
            return {
                classified: false,
                comment: '',
                sending: false
            }
        },
        methods: {
            submitComment() {
                if (this.comment && !this.sending) {
                    this.sending = true;
                    this.axios.post('classified/' + this.classified.id + '/comment', {message: this.comment})
                        .then((res) => {
                            this.comment = '';
                            this.sending = false;
                        }).catch(() => {
                        this.sending = false;
                    })
                }
            },
            deleteClassified() {
                this.axios.delete('classified/'+this.classified.id)
                    .then(() => {
                        this.$router.push('/club/classifieds');
                    })
            }
        },
        mounted() {
            if (this.$route.params.id) {
                this.axios.get('classified/'+this.$route.params.id)
                    .then((res) => {
                        this.classified = res.data;
                        this.$echo.channel('classified.' + this.classified.id)
                            .listen('ClassifiedCommentAdded', (e) => {
                                this.classified.comments.push(e.comment);
                            });
                    });
            }
        }
    }
</script>

<style scoped>

</style>