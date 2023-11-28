import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate'

import admin from './modules/admin';
import user from './modules/user';
import club from './modules/club';
import notifications from './modules/notifications';
import * as VueDeepSet from 'vue-deepset'

Vue.use(Vuex);

export default new Vuex.Store({
    plugins: [createPersistedState()],
    modules: {
        admin: admin,
        user: user,
        club: club,
        notifications: notifications
    },
    state: {
        spinner: false
    },
    mutations: {
        VUEX_DEEP_SET: VueDeepSet.VUEX_DEEP_SET,
        spinner(state) {
            state.spinner = !state.spinner;
        },
        spinnerDisable(state) {
            state.spinner = false;
        }
    },
    actions: {

    },
    getters: {
        isLoggedIn: state => {
            return state.user.isLoggedIn;
        },
        club: state => {
            if (window.location.hostname === 'www.' + process.env.MIX_DOMAIN) {
                return false;
            }
            return state.club
        },
        courts: state => {
            return state.club.courts;
        }
    },
    strict: false
})
