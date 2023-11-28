<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="text-center my-4">
                    <img class="avatar-l" :src="new_image?new_image:club.logo">
                </div>
                <div class="btn-box text-center mt-4">
                    <a class="btn btn-info btn-lg mr-2" @click.prevent="chooseFiles" role="button" v-if="!new_image">Promijeni fotografiju</a>
                    <input type="file" id="fileInput" v-on:change="previewImage($event.target)" accept="image/*" style="display: none;">
                    <a class="btn btn-success btn-lg" v-if="new_image" @click="saveImage">Spremi</a>
                    <a class="btn btn-danger btn-lg" v-if="new_image" @click="new_image=null">Poništi</a>
                </div>

                <b-form no-validate>
                    <div class="form-group mt-4">
                        <label for="clubname">Naziv kluba</label>
                        <input type="text" class="form-control" id="clubname" v-model="club.name">
                    </div>
                    <div class="form-group">
                        <label for="address">Adresa</label>
                        <input type="text" class="form-control" id="address" v-model="club.address">
                    </div>
                    <div class="form-group">
                        <label for="city">Mjesto</label>
                        <input type="text" class="form-control" id="city" v-model="club.city">
                    </div>
                    <div class="form-group" v-for="(email,index) in club.email" :key="'email'+index">
                        <label for="email">email</label>
                        <input type="email" class="form-control" id="email" v-model="club.email[index]">
                    </div>
                    <button class="btn btn-primary btn-sm mt-2" @click.prevent="club.email.push('')">Dodaj email</button>
                    <div class="form-group" v-for="(phone,index) in club.phone" :key="'phone'+index">
                        <label for="tel-input">Mobitel</label>
                        <input type="tel" class="form-control" id="tel-input" v-model="club.phone[index]">
                    </div>
                    <button class="btn btn-primary btn-sm mt-2" @click.prevent="club.phone.push('')">Dodaj telefon</button>
                    <div class="form-group">
                        <label for="about">O klubu</label>
                        <textarea class="form-control" id="about" rows="6" v-model="club.description"></textarea>
                    </div>
                    <div class="text-center mb-4">
                        <a class="btn btn-primary btn-lg mt-2" href="#" role="button" @click.prevent="submitClub">Spremi</a>
                    </div>
                </b-form>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "Club",
        data() {
            return {
                new_image: '',
                image2: '',
            }
        },
        computed: {
            club() {
                return this.$deepModel('club');
            }
        },
        methods: {
            submitClub() {
                this.$store.dispatch('club/save');
            },
            chooseFiles: function() {
                document.getElementById("fileInput").click();
            },
            previewImage(input) {
                if (input.files && input.files[0]) {
                    this.image2 = input;
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        this.new_image = e.target.result;
                    }.bind(this);

                    reader.readAsDataURL(input.files[0]);
                }
            },
            saveImage() {
                let data = new FormData();
                if (this.new_image) {
                    data.append('image', this.image2.files[0], this.image2.files[0].name);
                    data.append('_method', 'PUT');
                    this.$store.dispatch("club/save_image", data)
                        .then((res) => {
                            this.new_image = null;
                        })
                        .catch((err) => {
                        });
                }
            },
        }
    }
</script>

<style scoped>

</style>