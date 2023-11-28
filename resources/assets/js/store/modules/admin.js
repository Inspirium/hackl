import * as types from '../mutation-types';
export default {
    namespaced: true,
    state: {
        court: {
            id: '',
            name: '',
            is_active: true,
            type: '',
            working_from: '',
            working_to: '',
            lights: false,
            reservation_confirmation: true,
            reservation_duration: 60,
            surface_id: '',
            surface: {
                title: ''
            }
        },
        players: []
    },
    mutations: {
        [types.UPDATE_COURT](state, payload) {
            state.court[payload.key] = payload.value;
        },
        [types.ADMIN_LOAD_COURT](state, payload) {
            state.court = payload;
        },
        [types.ADMIN_LOAD_PLAYERS](state, payload) {
            state.players = payload;
        },
    },
    actions: {
        adminInitCourt({commit}, id) {
            window.axios.get('club/court/' + id)
                .then((res) => {
                    commit(types.ADMIN_LOAD_COURT, res.data);
                });
        },
        adminInitPlayers({commit}) {
            window.axios.get('club/players')
                .then((res) => {
                    commit(types.ADMIN_LOAD_PLAYERS, res.data);
                });
        }
    },
    getters: {
        court: (state) => {
            return state.court;
        },
        players: (state) => {
            return state.players;
        }
    }
}

