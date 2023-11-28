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
                            <div class="heading-section my-4">Prijavi se</div>
                        </div>
                        <!--<div class="text-center mt-2 mb-3">
                            <div class="social-icons icon-circle">
                                <div class="d-inline-block"><a href="#"><i class="fa fa-facebook"></i></a> </div>
                                <div class="d-inline-block ml-2"><a href="#"><i class="fa fa-google-plus"></i></a> </div>
                                <div class="d-inline-block ml-2"><a href="#"><i class="fa fa-twitter"></i></a> </div>
                            </div>
                        </div>-->

                        <div class="form-group">
                            <label for="email" class="col-md-12 control-label">E-Mail adresa</label>

                            <div class="col-md-12">
                                <input id="email" type="email" :class="['form-control', errors.has('email')?'is-invalid':'is-valid']" name="email" v-model="email">
                                <span class="invalid-feedback" v-show="errors.has('email')">
                                    <strong>{{ errors.first('email') }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-12 control-label">Lozinka</label>

                            <div class="col-md-12">
                                <input id="password" type="password" :class="['form-control', errors.has('password')?'is-invalid':'is-valid']" name="password" v-model="password">
                                <span class="invalid-feedback" v-show="errors.has('password')">
                                    <strong>{{ errors.first('password') }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 login-btns">
                                <button class="btn btn-lg btn-primary" @click.prevent="submitLogin">
                                    Prijavi se
                                </button>

                                <router-link to="/password/email" class="btn btn-sm btn-secondary">
                                    Zaboravili ste lozinku?
                                </router-link>
                            </div>
                        </div>
                        <div class="text-center mt-2 mb-4">
                            <div class="heading-mid-lighter border-top text-uppercase mt-2 mb-3 pt-3">Niste član kluba?</div>
                            <router-link class="btn btn-danger btn-lg" to="register">Registriraj se</router-link>
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
        data: function () {
            return {
                email: '',
                password: ''
            }
        },
        computed: {
            club() {
                return this.$store.getters.club;
            }
        },
        watch: {
            email(value) {
                this.$validator.validate('email', value)
            },
            password(value) {
                this.$validator.validate('password', value)
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
                        this.$store.dispatch("user/login", {
                            email: this.email,
                            password: this.password
                        })
                            .then(() => {
                                this.$router.push('/');
                            })
                            .catch((err) => {
                                if (err.response.status === 401) {
                                    this.errors.add('email', err.response.data.error);
                                    this.errors.add('password', err.response.data.error);
                                }
                                if (err.response.status === 422) {
                                    this.errors.add('email', err.response.data.error);
                                }
                                if (err.response.status === 500) {
                                    this.errors.add('email', 'Neispravni podaci za prijavu');
                                }
                                document.body.scrollTop = 0;
                                document.documentElement.scrollTop = 0;
                            });
                    }
                    else {
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                    }
                    this.$store.commit('spinner');
                });
            }
        },
        created() {
            this.$validator.attach({name: 'email', rules: 'required|email', alias: 'Email'});
            this.$validator.attach({name: 'password', rules: 'required|min:8', alias: 'Lozinka'});
        }
    }
</script>