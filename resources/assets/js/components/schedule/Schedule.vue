<template>
    <div class="container">
        <div class="row">
            <div class="col">
               <!--Class .inactive is for disabled arrow - user can't go to past dates -->
                <div class="date-picker mt-2">
                    <i v-bind:class="['fa fa-angle-left fa-4x', inactive?'inactive':'']" v-on:click="prevDay"></i>
                    <div class="heading-date">{{ selected_date | moment("dddd") }}, <span>{{ selected_date | moment("DD.MM.") }}</span></div>
                    <i class="fa fa-angle-right fa-4x" v-on:click="nextDay"></i>
                </div>
                <div class="schedule">
                    <div class="schedule-match" v-for="match in out_reservations">
                        <div class="schedule-court d-flex mt-2">
                            <div class="heading-small mr-auto">{{ match.from | moment('HH:mm') }}</div>
                            <div v-bind:class="['badge', 'badge-'+match.court.surface.badge, 'mb-1']">{{ match.court.name }}</div>
                        </div>
                        <div class="list-container">
                            <div class="list">
                                <div class="list-item mt-2">
                                    <div class="list-user">
                                        <a class="list-user versus">
                                            <img class="avatar-xs mr-2" :src="match.players[0].image">
                                            <div class="heading-mid align-self-center mr-2">{{ match.players[0].display_name }}</div>
                                        </a>
                                        <a class="list-user">
                                            <img class="avatar-xs mr-2" :src="match.players[1].image">
                                            <div class="heading-mid align-self-center mr-2">{{ match.players[1].display_name }}</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import _ from 'lodash';
    import moment from 'moment';
    export default {
        data: function () {
            return {
                selected_date: moment(),
                inactive: true,
                reservations: []
            }
        },
        computed: {
            user() {
                return this.$deepModel('user');
            },
            out_reservations() {
                return _.filter(this.reservations, function(item) {
                    return item.players.length > 1;
                })
            }
        },
        methods: {
            getSchedule() {
                this.axios.get('club/schedule/'+this.selected_date.format('YYYY-MM-DD'))
                    .then((res) => {
                        this.reservations = res.data.reservations;
                    })
            },
            nextDay() {
                this.selected_date = moment(this.selected_date).add(1, 'day');
                if (this.selected_date.isAfter(moment())) {
                    this.inactive = false;
                }
            },
            prevDay() {
                if (!this.inactive) {
                    this.selected_date = moment(this.selected_date).subtract(1, 'day');
                    if (this.selected_date.isSame(moment(), 'day')) {
                        this.inactive = true;
                    }
                }
            },
        },
        watch: {
            selected_date() {
                this.getSchedule();
            }
        },
        mounted() {
            this.getSchedule();
        }
    }
</script>