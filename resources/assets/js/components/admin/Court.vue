<template>
    <div class="container">
        <div class="row">
            <div class="col nopadding">
                <div class="header-image">
                    <div class="gradient"></div>
                    <div class="heading-image">Tereni</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="text-center my-4">
                    <a class="btn btn-info mt-2" href="#" data-toggle="modal"  data-target="#modal-court-free">Kopiraj podatke postojećeg terena</a>
                </div>

                <form>
                    <div class="form-group mt-4">
                        <label for="name">Ime terena</label>
                        <input id="name" class="form-control" v-model="court.name" placeholder="Teren broj ...">
                    </div>
                    <div class="form-radio">
                        <legend>Teren u funkciji</legend>
                        <input type="radio" id="court-working-yes" name="court-working" v-bind:value="true" v-model="court.is_active">
                        <label for="court-working-yes">Da</label>
                        <input type="radio" id="court-working-no" name="court-working" v-bind:value="false" v-model="court.is_active">
                        <label for="court-working-no">Ne</label>
                    </div>
                    <div class="form-group">
                        <label for="surface">Vrsta podloga</label>
                        <input id="surface" class="form-control"  placeholder="Odaberi podlogu" v-on:click="showModal('modalSurface')" readonly="true" v-bind:value="court.surface.title">
                    </div>
                    <div class="form-group" v-if="court.id">
                        <label class="only-bottom-line">radno vrijeme terena</label>
                        <div v-for="hours in court.working_hours">
                            <div class="court-time">
                                <div>{{ hours.cron | parseCron('days') }}</div>
                                <div>{{ hours.cron | parseCron('hours') }}</div>
                                <div>- {{ hours.price }}kn</div>
                                <button class="btn btn-sm btn-danger" @click.prevent="deleteHour(hours.id)">Obriši</button>
                            </div>
                        </div>
                        <div class="text-center mb-4">
                            <button class="btn btn-lg mt-4 btn-primary" @click.prevent="showModal('modalWorkingDays')">Dodaj radno vrijeme</button>
                        </div>
                    </div>

                    <div class="form-radio">
                        <legend>Reflektori</legend>
                        <input type="radio" id="lights-yes" name="lights" v-bind:value="true" v-model="court.lights">
                        <label for="lights-yes">Da</label>
                        <input type="radio" id="lights-no" name="lights" v-bind:value="false" v-model="court.lights">
                        <label for="lights-no">Ne</label>
                    </div>
                    <div class="form-radio">
                        <legend>Tip terena</legend>
                        <input type="radio" id="court-open" name="court-type" value="open" v-model="court.type">
                        <label for="court-open">Otvoreni</label>
                        <input type="radio" id="court-closed" name="court-type" value="closed" v-model="court.type">
                        <label for="court-closed">Zatvoreni</label>
                    </div>
                    <div class="form-radio">
                        <legend>Moguće rezervacije</legend>
                        <input type="radio" id="full-hour" name="reservation-block" value="60" v-model="court.reservation_duration">
                        <label for="full-hour">Puni sat</label>
                        <input type="radio" id="half-hour" name="reservation-block" value="30" v-model="court.reservation_duration">
                        <label for="half-hour">:30 minuta</label>
                    </div>
                    <div class="form-radio">
                        <legend>Potvrda rezervacije</legend>
                        <input type="radio" id="confirmation-yes" name="confirmation" v-bind:value="true" v-model="court.reservation_confirmation">
                        <label for="confirmation-yes">Da</label>
                        <input type="radio" id="confirmation-no" name="confirmation" v-bind:value="false" v-model="court.reservation_confirmation">
                        <label for="confirmation-no">Ne</label>
                    </div>

                    <div class="text-center mb-4">
                        <a class="btn btn-danger btn-lg mt-2" href="#" v-on:click="deleteCourt">Obriši</a>
                        <a class="btn btn-primary btn-lg mt-2" href="#" v-on:click="saveCourt">Spremi</a>
                    </div>
                </form>
            </div>
        </div>
        <b-modal ref="modalWorkingDays"
                 title="Radni sati"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="Dalje"
                 @cancel="showModal('modalWorkingHours')"
        >
            <div class="modal-container">
                <template v-for="(day, index) in days">
                    <div v-bind:class="getClass(index, 'days')"
                         v-on:click="selectTime(index, 'days')">
                        <div class="modal-text modal-price">{{ day }}</div>
                    </div>
                </template>
            </div>

        </b-modal>
        <b-modal ref="modalWorkingHours"
                 title="Radni sati"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="U redu"
                 @cancel="showModal('modalPrice')"
        >
            <div class="modal-container">
                <template v-for="hour in hours">
                    <div v-bind:class="getClass(hour, 'hours')"
                         v-on:click="selectTime(hour, 'hours')">
                        <div class="modal-text modal-price">{{ hour }}:00</div>
                    </div>
                </template>
            </div>

        </b-modal>
        <b-modal ref="modalPrice"
                 title="Cijena termina"
                 ok-variant="danger"
                 cancel-variant="success"
                 ok-title="Zatvori"
                 cancel-title="U redu"
                 @cancel="submitWorkingHours"
        >
            <div class="modal-container">
                <div class="search-bar mt-3">
                    <input class="form-control" type="number" placeholder="Unesi cijenu" id="example-search-input" v-model="price">
                    <div class="clearfix"></div>
                </div>
            </div>

        </b-modal>
        <b-modal id="modalSurface"
                 ref="modalSurface"
                 title="Podloga"
                 ok-variant="danger"
                 ok-title="Zatvori"
                 cancel-variant="success"
                 cancel-title="U redu"
        >
            <div class="modal-container">
                <div v-for="surface in surfaces" v-bind:class="['modal-element-white', 'modal-text', court.surface.id===surface.id?'active':'']" v-on:click="court.surface = surface; hideModal('modalSurface')">{{ surface.title }}</div>
            </div>
        </b-modal>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                court: {
                    surface: {
                        id: 0,
                        title: ''
                    }
                },
                days: ['Nedjelja', 'Ponedjeljak', 'Utorak', 'Srijeda', 'Četvrtak', 'Petak', 'Subota'],
                hours: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
                selected_days: [],
                selected_hours: [],
                price: 0,
                surfaces: []
            }
        },
        methods: {
            getClass(day, type) {
                return {
                    'modal-element-white': true,
                    'active': _.indexOf(this.$data['selected_'+type], day) > -1
                }
            },
            selectTime(day, type) {
                if (_.indexOf(this.$data['selected_'+type], day)>-1) {
                    this.$data['selected_'+type].splice(_.indexOf(this.$data['selected_'+type], day), 1);
                }
                else {
                    this.$data['selected_'+type].push(day);
                }
            },
            deleteCourt() {
                if (this.$route.params.id) {
                    this.axios.delete('admin/court/'+this.$route.params.id)
                        .then(() => {
                            this.$router.push('/admin');
                        })
                }
            },
            saveCourt() {
                if (this.$route.params.id) {
                    this.axios.put('admin/court/'+this.$route.params.id, this.court)
                        .then((res) => {
                            this.court = res.data;
                        });
                }
                else {
                    this.axios.post('admin/court', this.court)
                        .then((res) => {
                            this.$router.push('/admin/court/'+res.data.id);
                        });
                }
            },
            showModal(modal) {
                this.$refs[modal].show();
            },
            submitWorkingHours() {
                this.axios.post('admin/court/'+this.court.id+'/hours', {days: this.selected_days, hours: this.selected_hours, price: this.price})
                    .then((res) => {
                        this.court.working_hours.push(res.data);
                        this.selected_days = [];
                        this.selected_hours = [];
                        this.price = 0;
                    })
            },
            deleteHour(id) {
                this.axios.delete('admin/court/'+this.court.id+'/hours/'+id)
                    .then(() => {
                        this.court.working_hours = _.filter(this.court.working_hours, (h) => {
                            return h.id !== id;
                        });
                    });
            },
            getSurfaces() {
                this.axios.get('surfaces')
                    .then((res) => {
                        this.surfaces = res.data;
                    });
            },
            hideModal(modal) {
                this.$refs[modal].hide();
            },
        },
        filters: {
            parseCron(value, type) {
                let cron = value.split(' ');
                if (type === 'days') {
                    if (cron[4] === '*') {
                        return 'Svaki dan';
                    }
                    return cron[4]
                        .replace('0', 'Ned')
                        .replace('1', 'Pon')
                        .replace('2', 'Uto')
                        .replace('3', 'Sri')
                        .replace('4', 'Čet')
                        .replace('5', 'Pet')
                        .replace('6', 'Sub')
                        .replace('7', 'Ned')
                }
                if (type === 'hours') {
                    let hours = cron[1].split('-');
                    return hours[0] + ':00 - ' + hours[1] + ':00';
                }
                return value;
            }
        },
        mounted() {
            this.getSurfaces();
            if (typeof(this.$route.params.id) !== 'undefined') {
                this.axios.get('admin/court/'+this.$route.params.id)
                    .then((res) => {
                        this.court = res.data;
                    });
            }
        },
        beforeMount() {
            if (!this.$store.getters['user/isAdmin']) {
                this.$router.push('/');
            }
        }
    }
</script>