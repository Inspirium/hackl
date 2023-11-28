<template>
    <div class="container">
        <div class="row">
            <div class="col">
                    <div class="text-center my-4">
                        <croppa ref="profileImage"
                                :width="300"
                                :height="300"
                                placeholder="Odaberi sliku" d
                                :prevent-white-space="true"
                                :image-border-radius="300"
                                :show-remove-button="false"
                                :show-loading="true"
                                :replace-drop="true"
                                :initial-image="image"
                        >
                        </croppa>
                    </div>
                    <div class="btn-box text-center mt-4">
                        <a class="btn btn-info btn-lg mr-2" v-if="$refs.profileImage" @click.prevent="$refs.profileImage.chooseFile" role="button">Promijeni fotografiju</a>
                        <a class="btn btn-success btn-lg" v-if="$refs.profileImage && $refs.profileImage.chosenFile" @click="saveImage">Spremi</a>
                        <a class="btn btn-danger btn-lg" v-if="$refs.profileImage && $refs.profileImage.chosenFile" @click="cancelImage">Poništi</a>
                        <a class="btn btn-danger btn-lg mr-2" @click.prevent="removeImage">Ukloni fotografiju</a>
                    </div>
                <form>
                    <div class="form-group mt-4">
                        <label for="firstname">Ime</label>
                        <input type="text" :class="['form-control', errors.has('first_name')?'is-invalid':'is-valid']" id="firstname" v-model="first_name" name="first_name">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Prezime</label>
                        <input type="text" :class="['form-control', errors.has('last_name')?'is-invalid':'is-valid']" id="lastname" v-model="last_name" name="last_name">
                    </div>
                    <div class="form-radio">
                        <legend>Spol</legend>
                        <input type="radio" id="sex-m" name="sex" value="M" v-model="gender">
                        <label for="sex-m">M</label>
                        <input type="radio" id="sex-z" name="sex" value="F" v-model="gender">
                        <label for="sex-z">Ž</label>
                    </div>
                    <div class="form-group">
                        <label for="email-input">Email</label>
                        <input type="text" :class="['form-control', errors.has('email')?'is-invalid':'is-valid']" id="email-input" v-model="email" name="email">
                    </div>
                    <div class="btn-box text-center mt-4 mb-2">
                        <a class="btn btn-primary mr-2" href="#" role="button" v-on:click.prevent="change_password = !change_password">Promjeni lozinku</a>
                    </div>
                    <template v-if="change_password">
                        <div class="form-group">
                            <label for="password-input-old">Stara lozinka</label>
                            <input type="password" class="form-control" id="password-input-old" v-model="old_password" name="old_password">
                        </div>
                        <div class="form-group">
                            <label for="password-input-new">Nova lozinka</label>
                            <input type="password" :class="['form-control', errors.has('new_password')?'is-invalid':'is-valid']" id="password-input-new" v-model="new_password" name="new_password">
                        </div>
                        <div class="form-group">
                            <label for="password-input-new-confirm">Potvrdi novu lozinku</label>
                            <input type="password" :class="['form-control', errors.has('new_password_confirmation')?'is-invalid':'is-valid']" id="password-input-new-confirm" v-model="new_password_confirmation" name="new_password_confirmation">
                        </div>
                        <div class="btn-box text-center mt-4 mb-2">
                            <a class="btn btn-primary mr-2" href="#" role="button" v-on:click.prevent="savePassword">Spremi lozinku</a>
                        </div>
                    </template>

                    <div class="form-radio">
                        <legend>Želiš li biti član kluba</legend>
                        <input type="radio" id="member-yes" name="member" :value="true" v-model="club_member">
                        <label for="member-yes">Želim biti član kluba</label>
                        <input type="radio" id="member-no" name="member" :value="false" v-model="club_member">
                        <label for="member-no">Želim samo mogućnost rezerviranja terena</label>
                    </div>
                    <div class="form-group">
                        <label for="birthyear">Godina rođenja</label>
                        <input type="text" :class="['form-control', errors.has('birthyear')?'is-invalid':'is-valid']" id="birthyear" @click="showModal('modalBirth')" @focus="showModal('modalBirth')" readonly="true" v-model="birthyear">
                    </div>
                    <div class="form-group">
                        <label for="useraddress">Adresa</label>
                        <input type="text" :class="['form-control', errors.has('address')?'is-invalid':'is-valid']" id="useraddress" v-model="address">
                    </div>
                    <div class="form-group">
                        <label for="usercity">Mjesto</label>
                        <input type="text" :class="['form-control', errors.has('city')?'is-invalid':'is-valid']" id="usercity" v-model="city">
                    </div>
                    <div class="form-group">
                        <label for="tel-input">Mobitel</label>
                        <input type="tel" :class="['form-control', errors.has('phone')?'is-invalid':'is-valid']" id="tel-input" v-model="phone">
                    </div>

                    <div class="form-radio">
                        <legend>Dostupnost</legend>
                        <input type="radio" id="availability-yes" name="availability" value="yes" v-model="available">
                        <label for="availability-yes">Dostupan</label>
                        <input type="radio" id="availability-no" name="availability" value="no" v-model="available">
                        <label for="availability-no">Nisam dostupan</label>
                    </div>

                    <div class="text-center mb-4">
                        <a class="btn btn-primary btn-lg mt-2" href="#" role="button" v-on:click.prevent="saveProfile">Spremi</a>
                    </div>
                </form>
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
        data: function () {
            return {
                change_password: false,
                new_image: '',
                image: '',
                image2: '',
                first_name: '',
                last_name: '',
                birthyear: '',
                gender: '',
                email: '',
                old_password: '',
                new_password: '',
                new_password_confirmation: '',
                address: '',
                city: '',
                phone: '',
                club_member: '',
                available: ''
            }
        },
        computed: {
            user() {
                return this.$deepModel('user');
            },
            years() {
                return Array(2018 - 1920 + 1).fill().map((_, idx) => 2018 - idx)
            }
        },
        methods: {
            saveImage() {
                this.$refs.profileImage.generateBlob(
                    blob => {
                        let data = new FormData();
                        data.append('image', blob);
                        data.append('_method', 'PUT');
                        this.$store.dispatch("user/save_image", data)
                            .then((res) => {
                                this.image = res;
                                this.$refs.profileImage.refresh();
                            })
                            .catch((err) => {
                            });
                    },
                    'image/jpeg'
                );
            },
            removeImage() {
                this.$store.dispatch('user/remove_image')
                    .then((res) => {
                        this.image = res;
                        this.$refs.profileImage.refresh();
                    })
            },
            cancelImage() {
                this.$refs.profileImage.refresh();
            },
            saveProfile() {
                this.$validator.validateAll({
                    first_name: this.first_name,
                    last_name: this.last_name,
                    email: this.email,
                    birthyear: this.birthyear,
                    address: this.address,
                    city: this.city,
                    phone: this.phone,
                    gender: this.gender
                }).then((res) => {
                    if(res) {
                        let data = new FormData();
                        for ( let key in this.$data ) {
                            data.append(key, this.$data[key]);
                        }
                        if (this.new_image) {
                            data.append('image', this.image2.files[0], this.image2.files[0].name);
                        }
                        data.append('_method', 'PUT');
                        this.$store.dispatch("user/update", data)
                            .then((res) => {
                                this.$router.push(res.data.location);
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
                    }
                    else {
                        document.body.scrollTop = 0;
                        document.documentElement.scrollTop = 0;
                        return false;
                    }
                });
            },
            selectYear(year) {
                this.birthyear = year;
                this.hideModal('modalBirth');
            },
            savePassword() {
                this.axios.post('player/'+this.user.id+'/password', {old_password: this.old_password, new_password: this.new_password, new_password_confirmation: this.new_password_confirmation})
                    .then(() => {
                        this.change_password = false;
                    })
            },
            showModal(modal) {
                this.$refs[modal].show();
            },
            hideModal(modal) {
                this.$refs[modal].hide();
            },
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
            old_password(value) {
                if (this.change_password) {
                    this.$validator.validate('old_password', value)
                }
            },
            new_password(value) {
                if (this.change_password) {
                    this.$validator.validate('new_password', value)
                }
            },
            new_password_confirmation(value) {
                if (this.change_password) {
                    this.$validator.validate('new_password_confirmation', value)
                }
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
            this.$validator.attach({name: 'old_password', rules: 'required|min:8', alias: 'Stara Lozinka'});
            this.$validator.attach({name: 'new_password', rules: 'required|min:8|confirmed:new_password_confirmation', alias: 'Nova Lozinka'});
            this.$validator.attach({name: 'new_password_confirmation', rules: 'required|min:8', alias: 'Potvrda nove lozinke'});
            this.$validator.attach({name: 'birthyear', rules: 'required|date_format:YYYY', alias: 'Godina rođenja'});
            this.$validator.attach({name: 'address', rules: 'required', alias: 'Adresa'});
            this.$validator.attach({name: 'city', rules: 'required', alias: 'Grad'});
            this.$validator.attach({name: 'phone', rules: 'required|numeric|min:9', alias: 'Mobitel'});
            this.$validator.attach({name: 'gender', rules: 'required|in:M,F', alias: 'Spol'});
        },
        mounted() {
            for(let key in this.$data) {
                if (this.user.hasOwnProperty(key)) {
                    this.$data[key] = this.user[key];
                }
            }
            this.$refs.profileImage.refresh();
        }
    }
</script>