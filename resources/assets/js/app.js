import { routes } from './routes';

import Vue from 'vue';

import VueRouter from 'vue-router';
import VueMoment from 'vue-moment';
import BootstrapVue from 'bootstrap-vue';
import Vuex from 'vuex';
import store from './store/store';
import { sync } from 'vuex-router-sync';
import VeeValidate, { Validator } from 'vee-validate';
import hr from 'vee-validate/dist/locale/hr';
import * as VueDeepSet from 'vue-deepset';
import axios from 'axios'
import VueAxios from 'vue-axios'
import VueAnalytics from 'vue-analytics'
import Croppa from 'vue-croppa'

window._ = require('lodash');
window.cookie = require('cookie');

Validator.localize('hr', hr);

let instance = axios.create({
    baseURL: document.head.querySelector('meta[name="tennis-api"]').content,
    timeout: 10000,
    headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest',
        'Authorization': 'Bearer ' + store.state.user.access_token
    }
});
instance.interceptors.request.use(function(config) {
    config.headers['Authorization'] = 'Bearer ' + store.state.user.access_token;
    return config;
});

instance.interceptors.response.use(function (config) {
    return config;
}, function (error) {
    const originalRequest = error.config;
    if (error.response && error.response.status === 401 && !originalRequest._retry) {
        originalRequest._retry = true;
        let refresh_token = store.state.user.refresh_token;
        if (refresh_token) {
            return instance.post('login/refresh', {refresh_token: refresh_token})
                .then((data) => {
                    store.commit('user/set_tokens', data.data);
                    originalRequest.headers['Authorization'] = 'Bearer ' + data.access_token;
                    return axios(originalRequest);
                })
                .catch((error) => {
                    store.dispatch('user/logout');
                    router.push('/login');
                });
        }
        router.push('/login');
    }

    return Promise.reject(error);
});

window.axios = instance;

Vue.use(BootstrapVue);
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(VeeValidate);
Vue.use(VueDeepSet);
Vue.use(VueAxios, instance);
Vue.use(Croppa);

Vue.component('navbar', require('./components/partials/Header'));
Vue.component('court', require('./components/partials/Court'));
Vue.component('result-box', require('./components/partials/ResultBox'));
Vue.component('spinner', require('./components/partials/Spinner'));

const moment = require('moment');
require('moment/locale/hr');

Vue.use(VueMoment, {
    moment
});

const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes,
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    }
});

router.beforeEach((to, from, next) => {
    store.dispatch('club/init_club', Vue);
    store.commit('spinnerDisable');
    document.title = to.meta.title + ' - ' + store.state.club.name;
    if(to.meta.requiresAuth) {
        if(store.state.user.isLoggedIn) {
            next()
        } else {
            next({
                path : '/login'
            })
        }
    }

    next();
});

sync(store, router);

Vue.use(VueAnalytics, {
    id: 'UA-117903549-1',
    router
});

import VueEcho from 'vue-echo';
window.io = require('socket.io-client');

Vue.use(VueEcho, {
    broadcaster: 'socket.io',
    host: process.env.MIX_API_DOMAIN,
    auth: {
        headers: {
            Authorization: 'Bearer ' + store.state.user.access_token
        }
    }
});

const app = new Vue({
    el: '#app',
    store,
    router
});




