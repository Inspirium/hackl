<template>
    <div class="court mt-4">
        <router-link :to="'/club/court/'+court.id+'/'+selected_date.format('YYYY-MM-DD')">
            <div class="court-header d-flex">
                <div class="heading-small mr-auto">{{ court.name }}</div>
                <div v-bind:class="['badge', 'badge-'+court.surface.badge, 'mb-1']">{{ court.surface.title }}</div>
            </div>
            <div class="court-slots d-flex">
                <div class="court-slot" v-for="hour in court.working_hours">
                    <div class="heading-num-small text-center">{{ hour }}</div>
                    <template v-if="court.parsed_reservations[hour]">
                        <div v-bind:class="['indicator', court.parsed_reservations[hour].full?court.surface.reserved:'']">
                            <svg v-if="court.parsed_reservations[hour].half == 2" width="100%" height="100%" viewBox="0 0 10 10" preserveAspectRatio="none">
                                <polygon v-bind:fill="court.surface.fill" stroke-width=0 points="0,10 10,10 10,0" />
                            </svg>
                            <svg v-if="court.parsed_reservations[hour].half == 1" width="100%" height="100%" viewBox="0 0 10 10" preserveAspectRatio="none">
                                <polygon v-bind:fill="court.surface.fill" stroke-width=0 points="0 0,0 10,10 0"/>
                            </svg>
                        </div>
                    </template>
                    <template v-else>
                        <div class="indicator"></div>
                    </template>
                </div>
            </div>
            <div class="text-center btn-box mt-3">
                <a v-if="reserve" class="btn btn-info" href="#" v-on:click.prevent="showModal('modalTimetable', index)">Rezerviraj ovaj teren</a>
                <a v-if="cancel" class="btn btn-danger btn-lg" href="#" role="button">Otkaži</a>
                <!--Admin-only-->
                <router-link v-if="isAdmin" class="btn btn-info btn-sm" v-bind:to="'admin/court/'+court.id">Uredi podatke</router-link>
                <router-link v-if="isAdmin" class="btn btn-info" v-bind:to="'court/'+court.id">Detaljnije</router-link>
                <!--End-->
            </div>
        </router-link>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        name: "Court",
        props: ['court_id', 'date', 'reserve', 'cancel'],
        data() {
            return {
            }
        },
        computed: {
            user() {
                return this.$store.state.user;
            },
            isAdmin() {
                return this.$store.getters['user/isAdmin'];
            },
            court() {
                return _.find(this.$store.state.club.courts, (court) => {
                    return court.id === this.court_id;
                })
            },
            selected_date() {
                return moment(this.date);
            }
        }
    }
</script>

<style scoped>

</style>