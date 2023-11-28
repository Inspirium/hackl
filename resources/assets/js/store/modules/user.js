import moment from 'moment';

let initial_state = {
    id: 0,
    display_name: '',
    first_name: '',
    last_name: '',
    gender: '',
    email: '',
    club_member: '',
    birthyear: '',
    address: '',
    city: '',
    phone: '',
    available: '',
    login_error: '',
    image: '',
    club_rank: 0,
    range: '',
    score: '',
    pending: false,
    notifications: [],
    isLoggedIn: false,
    admin: [],
    last_update: 0
};
export default {
    namespaced: true,
    namespace: 'user',
    state: {
        id: 0,
        display_name: '',
        first_name: '',
        last_name: '',
        gender: '',
        email: '',
        club_member: '',
        birthyear: '',
        address: '',
        city: '',
        phone: '',
        image: '/images/user.svg',
        available: '',
        login_error: '',
        club_rank: 0,
        range: '',
        score: '',
        rounded_power: '',
        pending: false,
        notifications: [],
        isLoggedIn: false,
        admin: [],
        access_token: '',
        refresh_token: '',
        expires: '',
        last_update: 0
    },
    mutations: {
        login(state) {
            state.pending = true;
        },
        login_success(state, payload) {
            state.isLoggedIn = true;
            state.pending = false;
            state.id = payload.id;
            state.display_name = payload.display_name;
            state.first_name = payload.first_name;
            state.last_name = payload.last_name;
            state.email = payload.email;
            state.club_member = payload.club_member;
            state.birthyear = payload.birthyear;
            state.address = payload.address;
            state.city = payload.city;
            state.phone = payload.phone;
            state.available = payload.available;
            state.image = payload.image;
            state.gender = payload.gender;
            state.admin = payload.admin;
            state.rank_club = payload.rank_club;
            state.range = payload.range;
            state.score = payload.score;
            state.rounded_power = payload.rounded_power;
            state.last_update = moment();
        },
        logout(state) {
            for (let i in Object.keys(initial_state)) {
                let key = Object.keys(initial_state)[i];
                state[key] = initial_state[key];
            }
            state.access_token = '';
            state.refresh_token = '';
        },
        login_failure(state, payload) {
            state.pending = false;
            state.login_error = payload;
        },
        set_tokens(state, payload) {
            state.access_token = payload.access_token;
            state.refresh_token = payload.refresh_token;
            state.expires = moment().add(payload.expires_in, 'seconds');
        },
        update_image(state, payload) {
            state.image = payload;
        }
    },
    actions: {
        login({ commit, state, rootState }, creds) {
            commit('login'); // show spinner
            return new Promise((resolve, reject) => {
                window.axios.post('login', {email: creds.email, password: creds.password})
                    .then((response) => {
                        if (typeof(response.data.error) !== 'undefined') {
                            commit('login_failure', response.data.error);
                            reject();
                        }
                        else {
                            let user = response.data.user;
                            commit('login_success', user);
                            commit('set_tokens', response.data);
                            resolve();
                        }
                    })
                    .catch((err) => {
                        commit('login_failure', 'Undefined error');
                        reject(err);
                    });
            });
        },
        logout({ commit }) {
            commit('logout');
        },
        update({commit, state}, payload) {
            return new Promise((resolve, reject) => {
                if (state.id) {
                    window.axios.post('player/' + state.id, payload)
                        .then((response) => {
                            commit('login_success', response.data.user);
                            resolve(response);
                        })
                        .catch((err) => {
                            reject(err);
                        });
                }
            })
        },
        remove_image({commit, state}) {
            return new Promise((resolve, reject) => {
                if (state.id) {
                    window.axios.put('player/'+state.id+'/remove_image')
                        .then((res) => {
                            commit('update_image', res.data);
                            resolve(res.data);
                        })
                        .catch(reject);
                }
                else {
                    reject();
                }
            })
        },
        save_image({commit, state}, data) {
            return new Promise((resolve, reject) => {
                if (state.id) {
                    window.axios.post('player/' + state.id + '/save_image', data)
                        .then((res) => {
                            commit('update_image', res.data);
                            resolve(res.data);
                        })
                        .catch((err) => {
                            reject(err);
                        });
                }
                else {
                    reject();
                }
            })
        },
        create({commit, state}, data) {
            return new Promise((resolve, reject) => {
                window.axios.post('player', data)
                    .then((response) => {
                        commit('login_success', response.data.user);
                        resolve(response);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        register({commit, state, rootState}, data) {
            return new Promise((resolve, reject) => {
                window.axios.post('register', data)
                    .then((response) => {
                        let user = response.data.user;
                        commit('login_success', user);
                        commit('set_tokens', response.data);
                        resolve(response);
                    })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        refresh({commit, state}, data) {
            window.axios.get('/me')
                .then((res) => {
                    commit('login_success', res.data);
                });
        }
    },
    getters: {
        isAdmin(state, getters, rootState) {
            return state.club_member[rootState.club.id]==='admin';
        }
    }
}
