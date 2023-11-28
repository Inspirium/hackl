<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Objavi oglas</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">

                <div class="filter filter-clasified">
                    <div class="filter-box">
                        <div class="filter-item">
                            <div class="filter-label">Kategorija</div>
                            <div class="form-group">
                                <input type="text" class="form-control bg-white" @click.prevent="showModal('modalCategory')" readonly="true" :placeholder="options[selected_category]">
                                <i class="fa fa-caret-down icon-caret" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row mt-4">
                    <label for="title" class="col control-label">Naslov oglasa</label>
                    <div class="col-md-12">
                        <input type="text" :class="['form-control', errors.has('title')?'is-invalid':'is-valid']" id="title" name="title" v-model="title">
                    </div>
                </div>

                <div class="form-group">
                    <label class=" mb-1">Opis</label>
                    <textarea class="form-control" id="message" rows="6" v-model="description"></textarea>
                </div>

                <div class="form-radio">
                    <legend class="mt-3 mb-1">Priloži fotografiju</legend>
                    <img class="classified_img" :src="new_image?new_image:image" alt="">
                    <div class="btn-box text-center mt-4">
                        <a class="btn btn-primary" role="button" @click.prevent="chooseFiles">Priloži fotografiju</a>
                        <input type="file" id="fileInput" v-on:change="previewImage($event.target)" accept="image/*" style="display: none;">
                        <a class="btn btn-danger" role="button" @click.prevent="removeImage">Obriši fotografiju</a>
                    </div>
                </div>

               <div class="form-group row mt-4">
                    <label for="price" class="col control-label">Cijena u kunama</label>
                    <div class="col-md-12">
                        <input type="number" :class="['form-control', errors.has('price')?'is-invalid':'is-valid']" id="price" name="price" v-model="price">
                    </div>
                </div>

               <div class="btn-box text-center mt-4">
                    <a class="btn btn-primary btn-lg" @click.prevent="save" role="button">Objavi oglas</a>
                </div>

            </div>
        </div>
        <b-modal ref="modalCategory"
                     title="Kategorije"
                     ok-variant="danger"
                     ok-title="Zatvori"
                     @cancel="getClassifieds"
                     cancel-variant="success"
                     cancel-title="U redu">
                <div class="modal-container">
                    <div v-for="(option, key) in options" v-bind:class="['modal-element-white', 'modal-text', selected_category===key?'active':'']" v-on:click="selected_category = key">{{ option }}</div>
                </div>
            </b-modal>
    </div>
</template>

<script>
    export default {
        name: "Results",
        data() {
            return {
                title: '',
                description: '',
                price: '',
                image: '/images/static/classified-placeholder.jpg',
                new_image: false,
                selected_category: 'all',
                options: {
                    'all': 'Sve',
                    'racket': 'Reketi',
                    'bags': 'Torbe',
                    'other': 'Ostala oprema'
                }
            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            }
        },
        methods: {
            showModal(modal) {
                this.$refs[modal].show();
            },
            hideModal(modal) {
                this.$refs[modal].hide();
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
            removeImage() {
                this.new_image = false;
            },
            save() {
                if (this.$route.params.id) {
                    this.axios.put('classified', {title: this.title, description: this.description, price: this.price, category: this.selected_category})
                        .then((res) => {
                            this.$router.push(res.data.link);
                        })
                }
                else {
                    let data = new FormData();
                    data.append('title', this.title);
                    data.append('description', this.description);
                    data.append('price', this.price);
                    data.append('category', this.selected_category);
                    if (this.new_image) {
                        data.append('image', this.image2.files[0], this.image2.files[0].name);
                    }
                        this.axios.post('classified', data)
                            .then((res) => {
                                this.$router.push(res.data.link);
                            })
                }
            }
        },
        mounted() {
            if (this.$route.params.id) {
                this.axios.get('classified/'+this.$route.params.id)
                    .then((res) => {
                        this.title = res.data.title;
                        this.description = res.data.description;
                        this.selected_category = res.data.category;
                        this.image = res.data.image;
                        this.price = res.data.price;
                    })
            }
        }
    }
</script>

<style scoped>

</style>