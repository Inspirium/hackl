<template>
    <div class="result-box" v-if="result.id">
        <div class="result-label">
            <div class="heading-small mr-auto"><router-link :to="'/club/results/result/'+result.id">{{ result.date | moment('DD.MM.Y.') }}</router-link></div>
            <div v-if="result.court_id" :class="['badge', 'badge-'+result.court.badge, 'mb-1']">{{ result.court.title }}</div>
            <div v-if="isNotVerified" class="badge badge-danger mb-1 ml-1">Neverificiran</div>
        </div>
        <div class="result-item" v-if="result.players[0]">
            <div class="result-wrapper">
                <div class="result-players">
                    <router-link :to="'/player/'+result.players[0].id"><img class="avatar-xs mb-2" :src="result.players[0].image"></router-link>
                    <router-link :to="'/player/'+player2.id"><img class="avatar-xs" :src="player2.image"></router-link>
                </div>
                <div class="result-score-box">
                    <div class="result-score-label--top">{{ result.players[0].display_name}}</div>
                    <div class="result-score">

                        <!-- Repeate this for every set. -->
                        <div class="result-score-set" v-for="set in result.sets" v-if="set[0] || set[1]">
                            <div :class="['result-score-gem', set[0]>set[1]?'btn-info':'btn-info-light']">{{set[0]}}</div>
                            <div :class="['result-score-gem', set[0]<set[1]?'btn-info':'btn-info-light']">{{set[1]}}</div>
                        </div>
                        <div class="result-score-win">
                            <div class="won">
                                <img :class="result.winner?'align-self-end':'align-self-start'" src="/images/sucess-arrow.svg" width="30">
                            </div>
                        </div>
                    </div>
                    <div class="result-score-label--bottom">{{ player2.display_name}}</div>
                </div>
            </div>
            <router-link :to="'/club/results/result/'+result.id" class="result-comments" >{{ comment_count }}</router-link>
        </div>
        <div class="btn-box text-center mt-3" >
            <a v-if="canVerify" class="btn btn-success btn-md mr-2" href="#" role="button" @click.prevent="showModal('modalVerify')">Verificiraj rezultat</a>
            <a v-if="canDispute" class="btn btn-danger btn-md mr-2" href="#" role="button" @click.prevent="showModal('modalDispute')">Ospori rezultat</a>
            <a v-if="canDelete" class="btn btn-danger btn-md mr-2" href="#" role="button" @click.prevent="showModal('modalDelete')">Obriši rezultat</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ResultBox",
        props: {
            result: {
                type: Object,
                required: true
            },
            verify: {
                type: Boolean,
                default: false
            },
            delete: {
                type: Boolean,
                default: false
            },
            dispute: {
                type: Boolean,
                default: false
            }
            },
        data() {
            return {}
        },
        computed: {
            user() {
                return this.$store.state.user;
            },
            isAdmin() {
                return this.$store.getters['user/isAdmin'];
            },
            comment_count() {
                if (this.result) {
                    if (this.result.comments) {
                        return this.result.comments.length;
                    }
                    return this.result.comment_count;
                }
                return 0;
            },
            canDelete() {
                return (this.delete && !this.result.verified && !this.result.players[1].pivot.verified && (this.result.players[0].id === this.user.id || this.isAdmin));
            },
            canDispute() {
                if (this.result.non_member) {
                    return false;
                }
                return (this.dispute && !this.result.verified && !this.result.players[1].pivot.verified && (this.result.players[1].id === this.user.id));
            },
            canVerify() {
                if (this.result.non_member) {
                    return false;
                }
                return (this.verify && this.isNotVerified && this.user.id === this.result.players[1].id);
            },
            isNotVerified() {
                if (this.result.non_member) {
                    return true;
                }
                return !(this.result.players[0].pivot.verified && this.result.players[1].pivot.verified);
            },
            player2() {
                if (this.result.players[1]) {
                    return this.result.players[1];
                }
                else {
                    return {
                        display_name: this.result.non_member,
                        image: '/images/user.svg'
                    }
                }
            }
        },
        methods: {
            hideModal(modal) {
                this.$emit('hideModal', modal);
            },
            showModal(modal) {
                this.$emit('showModal', modal, this.result.id);
            }
        }
    }
</script>