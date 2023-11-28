<template>
    <div class="container">
        <div class="login-image full-width">
            <div class="panel panel-default">
                <div class="col nopadding">
                    <div class="header-image-login text-center">
                        <img class="avatar-m align-self-center mt-3" v-bind:src="club.logo">
                        <div class="gradient"></div>
                        <div class="heading-image">{{ club.name }}</div>
                    </div>
                </div>
                <div class="panel-body d-flex flex-column">
                    <b-form class="form-horizontal" v-on:submit.prevent="submitLogin" novalidate v-if="!pass_sent">

                        <div class="text-center d-flex flex-column">
                            <div class="heading-section my-4">Nova lozinka</div>
                        </div>

                        <div class="summary-display-players">Nakon što zatražite novu lozinku, u vaš e-mail sandučić doći će poruka s gumbom za resetiranje lozinke. Klikom na taj gumb dobiti ćete mogućnost upisivanje nove lozinke</div>


                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">E-Mail adresa</label>

                            <div class="col-md-6">
                                <input id="email" type="email" :class="['form-control', errors.has('email')?'is-invalid':'is-valid']" name="email" v-model="email">
                                <span class="invalid-feedback" v-show="errors.has('email')">
                                    <strong>{{ errors.first('email') }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button class="btn btn-lg btn-primary" @click.prevent="submitLogin">
                                    Zatraži novu lozinku
                                </button>
                            </div>
                        </div>
                    </b-form>
                    <div v-else>
                        <div class="text-center d-flex flex-column">
                            <div class="heading-section my-4">Nova lozinka</div>
                        </div>

                        <div class="summary-display-players">Email s poveznicom za oporavak lozinke je poslan na Vašu adresu.</div>

                    </div>
                </div>
            </div>
        </div>
        <spinner></spinner>
    </div>
</template>

<script>
    export default {
        name: "PasswordEmail",
        data() {
            return {
                email: '',
                pass_sent: false
            }
        },
        computed: {
            club() {
                return this.$store.state.club;
            }
        },
        watch: {
            email(value) {
                this.$validator.validate('email', value)
            },
        },
        methods: {
            submitLogin() {
                this.$store.commit('spinner');
                this.$validator.validateAll({
                    email: this.email
                }).then((res) => {
                    if (res) {
                        axios.post('password/email', {email: this.email})
                            .then((res) => {
                                //inform user to check email
                                this.pass_sent = true;
                                this.$store.commit('spinner');
                            }).catch((err) => {
                                this.errors.add('email', 'Dogodila se greška pri slanju poruke. Probajte ponovo.')
                            this.$store.commit('spinner');
                        });
                    }
                    else {
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                        this.$store.commit('spinner');
                    }
                });

            }
        },
        created() {
            this.$validator.attach({name: 'email', rules: 'required|email', alias: 'Email'});
        }
    }
</script>

<style scoped>

</style>