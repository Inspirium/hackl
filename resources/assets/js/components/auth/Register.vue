<template>
    <div class="container">
        <div class="login-image full-width">
            <div class="panel panel-default">
                <div class="col nopadding">
                    <div class="header-image-login text-center">
                        <img class="avatar-m align-self-center mt-3" v-bind:src="club.logo">
                        <div class="gradient"></div>
                        <div class="heading-image">{{ club.name || 'Tenis.plus' }}</div>
                    </div>
                </div>
                <div class="panel-body">
                    <div v-if="error" class="invalid-feedback">
                        <strong>{{ error }}</strong>
                    </div>
                    <b-form class="form-horizontal" v-on:submit.prevent="handleSubmit" novalidate>
                        <input type="text" name="username" v-model="email" v-show="false">
                        <input type="text" name="password" v-model="password" v-show="false">
                        <div class="text-center d-flex flex-column">
                            <div class="heading-section my-4">Registriraj se</div>
                        </div>
                        <div class="form-group">
                            <label for="first_name" class="col-md-4 control-label">Ime</label>
                            <div class="col-md-6">
                                <input id="first_name" :class="['form-control', errors.has('first_name')?'is-invalid':'is-valid']" name="first_name" v-model="first_name">
                                <span class="invalid-feedback" v-show="errors.has('first_name')">
                                    <strong>{{ errors.first('first_name') }}</strong>
                                </span>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="col-md-4 control-label">Prezime</label>

                            <div class="col-md-6">
                                <input id="last_name" :class="['form-control', errors.has('last_name')?'is-invalid':'is-valid']" name="last_name" v-model="last_name">
                                <span class="invalid-feedback" v-show="errors.has('last_name')">
                                        <strong>{{ errors.first('last_name') }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">E-Mail Adresa</label>

                            <div class="col-md-6">
                                <input id="email" type="email" :class="['form-control', errors.has('email')?'is-invalid':'is-valid']" name="email" v-model="email">
                                <span class="invalid-feedback" v-show="errors.has('email')">
                                    <strong>{{ errors.first('email') }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="birthyear" class="col-md-4 control-label">Godina rođenja</label>
                            <div class="col-md-6">
                            <input type="text" :class="['form-control', errors.has('birthyear')?'is-invalid':'is-valid']" id="birthyear" @click="showModal('modalBirth')" @focus="showModal('modalBirth')" readonly v-model="birthyear">
                                <span class="invalid-feedback" v-show="errors.has('birthyear')">
                                    <strong>{{ errors.first('birthyear') }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-radio">
                            <div class="col-12">
                                <legend>Spol</legend>
                                <input type="radio" id="sex-m" name="gender" value="M" v-model="gender">
                                <label for="sex-m">M</label>
                                <input type="radio" id="sex-z" name="gender" value="F" v-model="gender">
                                <label for="sex-z">Ž</label>
                                <span class="invalid-feedback" v-show="errors.has('gender')">
                                    <strong>{{ errors.first('gender') }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address" class="col-md-4 control-label">Adresa</label>
                            <div class="col-md-6">
                            <input type="text" :class="['form-control', errors.has('address')?'is-invalid':'is-valid']" id="address" name="address" v-model="address">
                                <span class="invalid-feedback" v-show="errors.has('address')">
                                    <strong>{{ errors.first('address') }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city" class="col-md-4 control-label">Mjesto</label>
                            <div class="col-md-6">
                            <input type="text" :class="['form-control', errors.has('city')?'is-invalid':'is-valid']" id="city" name="city" v-model="city">
                                <span class="invalid-feedback" v-show="errors.has('city')">
                                    <strong>{{ errors.first('city') }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tel-input" class="col-md-4 control-label">Mobitel</label>
                            <div class="col-md-6">
                            <input type="tel" :class="['form-control', errors.has('phone')?'is-invalid':'is-valid']" id="tel-input" name="phone" placeholder="" v-model="phone">
                                <span class="invalid-feedback" v-show="errors.has('phone')">
                                    <strong>{{ errors.first('phone') }}</strong>
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
                            <label for="password-confirm" class="col-md-4 control-label">Potvrdi lozinku</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" :class="['form-control', errors.has('password_confirmation')?'is-invalid':'is-valid']" name="password_confirmation" v-model="password_confirmation">
                                <span class="invalid-feedback" v-show="errors.has('password')">
                                    <strong>{{ errors.first('password_confirmation') }}</strong>
                                </span>
                            </div>
                        </div>

                        <div class="form-radio" v-if="club">
                            <div class="col-md-6">
                                <legend>Želiš li biti član kluba</legend>
                                <input type="radio" id="member-yes" name="member" :value="1" v-model="member">
                                <label for="member-yes">Želim biti član kluba</label>
                                <input type="radio" id="member-no" name="member" :value="0" v-model="member">
                                <label for="member-no">Želim samo mogućnost rezerviranja terena</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-center my-4">
                            <croppa ref="profileImage"
                                    :width="300"
                                    :height="300"
                                    placeholder="Odaberi sliku"
                                    :prevent-white-space="true"
                                    :image-border-radius="300"
                                    :show-loading="true"
                                    :replace-drop="true"
                            >
                            </croppa>
                            </div>
                        </div>

                        <div class="text-center mt-2 mb-4">
                            <button class="btn btn-danger btn-lg">Registriraj se</button>
                        </div>
                    </b-form>
                </div>
            </div>
        </div>
        <b-modal id="modalYear"
                 ref="modalBirth"
                 title="Odaberi godinu rođenja"
                 ok-only
                 @ok="hideModal('modalBirth')"
                 ok-title="Zatvori"
                 ok-variant="success"
                 title-tag="div"
        >
            <div class="modal-container">
                <div v-for="year in years" v-bind:class="['modal-element-white', 'modal-text', birthyear===year?'active':'']" v-on:click="selectYear(year)">{{ year }}</div>
            </div>
        </b-modal>
    </div>
</template>
<script>
    export default {
        validator: null,
        data: function () {
            return {
                first_name: '',
                last_name: '',
                email: '',
                password: '',
                password_confirmation: '',
                member: 1,
                birthyear: '',
                address: '',
                city: '',
                phone: '',
                gender: 'M',
                error: null
            }
        },
        computed: {
            club() {
                return this.$store.getters.club;
            },
            years() {
                return Array(2000 - 1920 + 1).fill().map((_, idx) => 2000 - idx)
            }
        },
        methods: {
            handleSubmit() {
                this.$validator.validateAll({
                    first_name: this.first_name,
                    last_name: this.last_name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    birthyear: this.birthyear,
                    address: this.address,
                    city: this.city,
                    phone: this.phone,
                    gender: this.gender
                }).then((res) => {
                    if(res) {
                        this.$refs.profileImage.generateBlob(
                            blob => {
                                let data = new FormData();
                                data.append('image', blob);
                                data.append('first_name', this.first_name);
                                data.append('last_name', this.last_name);
                                data.append('email', this.email);
                                data.append('password', this.password);
                                data.append('password_confirmation', this.password_confirmation);
                                data.append('birthyear', this.birthyear);
                                data.append('address', this.address);
                                data.append('city', this.city);
                                data.append('phone', this.phone);
                                data.append('gender', this.gender);
                                this.$store.dispatch("user/register", this.$data)
                                    .then((res) => {
                                        this.$router.push('/');
                                    })
                                    .catch((err) => {
                                        if (err.response.status === 422) {
                                            for (let i in err.response.data.errors) {
                                                this.errors.add(i, err.response.data.errors[i][0]);
                                                document.body.scrollTop = 0;
                                                document.documentElement.scrollTop = 0;
                                            }
                                        }
                                        if (err.response.status === 500) {
                                            this.error = err.response.data.error;
                                            document.body.scrollTop = 0; // For Safari
                                            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                                        }
                                    });
                            },
                            'image/jpeg'
                        );

                    }
                    else {
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                        return false;
                    }
                });
                return false;
            },
            showModal(modal) {
                this.$refs[modal].show();
            },
            hideModal(modal) {
                this.$refs[modal].hide();
            },
            selectYear(year) {
                this.birthyear = year;
                this.hideModal('modalBirth');
            }
        },
        watch: {
            email(value) {
                this.$validator.validate('email', value)
            },
            first_name(value) {
                this.$validator.validate('first_name', value)
            },
            last_name(value) {
                this.$validator.validate('last_name', value)
            },
            password(value) {
                this.$validator.validate('password', value)
            },
            birthyear(value) {
                this.$validator.validate('birthyear', value)
            },
            address(value) {
                this.$validator.validate('address', value)
            },
            city(value) {
                this.$validator.validate('city', value)
            },
            phone(value) {
                this.$validator.validate('phone', value)
            },
            gender(value) {
                this.$validator.validate('gender', value)
            }
        },
        created() {
            this.$validator.attach({name: 'email', rules: 'required|email', alias: 'Email'});
            this.$validator.attach({name: 'first_name', rules: 'required|alpha|min:2', alias: 'Ime'});
            this.$validator.attach({name: 'last_name', rules: 'required|alpha|min:2', alias: 'Prezime'});
            this.$validator.attach({name: 'password', rules: 'required|min:8', alias: 'Lozinka'});
            this.$validator.attach({name: 'password_confirmation', rules: 'required', alias: 'Lozinka'});
            this.$validator.attach({name: 'birthyear', rules: 'required|date_format:YYYY', alias: 'Godina rođenja'});
            this.$validator.attach({name: 'address', rules: 'required', alias: 'Adresa'});
            this.$validator.attach({name: 'city', rules: 'required', alias: 'Grad'});
            this.$validator.attach({name: 'phone', rules: 'required|numeric|min:9', alias: 'Mobitel'});
            this.$validator.attach({name: 'gender', rules: 'required|in:M,F', alias: 'Spol'});
        }
    }
</script>