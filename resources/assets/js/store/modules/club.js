import moment from 'moment';
export default {
    namespaced: true,
    namespace: 'club',
    state: {
        name: 'Teniski klub',
        logo: '/images/logo.jpg',
        club_loaded: false,
        courts: [],
        filters: {
            surface: 'any',
            date: ''
        },
        address: '',
        city: '',
        county: '',
        description: '',
        email: [],
        fax: [],
        id: 0,
        is_active: false,
        latitude: '',
        longitude: '',
        phone: [],
        postal_code: '',
        region: '',
        subdomain: '',
        weather: '',
        main_thread: 0,
        last_update: 0,
        surfaces: [],
        canceled: {}
    },
    mutations: {
        init_club(state, club) {
            for (let i in Object.keys(state)) {
                let key = Object.keys(state)[i];
                if (typeof club[key] !== 'undefined') {
                    state[key] = club[key];
                }
            }
            state.last_update = moment();
            state.club_loaded = club.domain;
        },
        load_courts(state, courts) {
            state.courts = courts;
        },
        update_court_filter(state, payload) {
            state.filters[payload.filter] = payload.value;
        },
        update_image(state, payload) {
            state.logo = payload;
        }
    },
    actions: {
        init_club({ commit, state }) {
            if (!state.club_loaded || state.club_loaded !== window.location.hostname || state.last_update < moment().subtract(1, 'd') ) {
                window.axios.get('club')
                    .then((res) => {
                        if (Object.keys(res.data).length) {
                            commit('init_club', res.data);
                        }
                        else {
                           // window.location.href = '/';
                        }
                    })
                    .catch(()=>{

                    });
            }
        },
        get_courts({ commit }, day) {
            window.axios.get('club/courts/'+day)
                .then((res) => {
                    commit('load_courts', res.data);
                });
        },
        submit_times({ dispatch }, payload) {
            return new Promise( (resolve, reject) => {
                window.axios.post('club/reservation', payload)
                    .then((res) => {
                        dispatch('get_courts', payload.date);
                        resolve(res.data);
                    })
                    .catch((err) => {
                        reject();
                    });
            });
        },
        save({commit, state}) {
            window.axios.put('admin/club/'+state.id, state)
                .then((res) => {
                commit('init_club', res.data);
            })
        },
        save_image({commit, state}, data) {
            return new Promise((resolve, reject) => {
                if (state.id) {
                    window.axios.post('club/save_image', data)
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
    }
}