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
                    <b-form class="form-horizontal" v-on:submit.prevent="submitLogin" novalidate>

                        <div class="text-center d-flex flex-column">
                            <div class="heading-section my-4">Promijeni lozinku</div>
                        </div>

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
                            <label for="password" class="col-md-4 control-label">Lozinka</label>

                            <div class="col-md-6">
                                <input id="password" type="password" :class="['form-control', errors.has('password')?'is-invalid':'is-valid']" name="password" v-model="password">
                                <span class="invalid-feedback" v-show="errors.has('password')">
                                    <strong>{{ errors.first('password') }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="col-md-4 control-label">Potvrda Lozinke</label>

                            <div class="col-md-6">
                                <input id="password_confirmation" type="password" :class="['form-control', errors.has('password_confirmation')?'is-invalid':'is-valid']" name="password_confirmation" v-model="password_confirmation">
                                <span class="invalid-feedback" v-show="errors.has('password')">
                                    <strong>{{ errors.first('password_confirmation') }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button class="btn btn-lg btn-primary" @click.prevent="submitLogin">
                                    Spremi lozinku
                                </button>
                            </div>
                        </div>
                    </b-form>
                </div>
            </div>
        </div>
        <spinner></spinner>
    </div>
</template>

<script>
    export default {
        name: "PasswordReset",
        data() {
            return {
                email: '',
                password: '',
                password_confirmation: ''
            }
        },
        computed: {
            club() {
                return this.$store.state.club;
            },
            code() {
                return this.$route.params.token;
            }
        },
        methods: {
            submitLogin() {
                this.$store.commit('spinner');

                this.$validator.validateAll({
                    email: this.email,
                    password: this.password
                }).then((res) => {
                    if (res) {
                        this.axios.post('password/reset', {
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation,
                            token: this.code
                        })
                            .then((res) => {
                                //login user
                                this.$router.push('/login');
                                this.$store.commit('spinner');
                            }).catch((err) => {
                                this.errors.add('email', 'Nismo uspjeli promijeniti lozinku. Probajte ponovo.')
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
            this.$validator.attach({name: 'password', rules: 'required|min:8', alias: 'Lozinka'});
        },
        watch: {
            email(value) {
                this.$validator.validate('email', value)
            },
            password(value) {
                this.$validator.validate('password', value)
            }
        },
    }
</script>