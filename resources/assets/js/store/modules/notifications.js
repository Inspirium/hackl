export default {
    namespaced: true,
    namespace: 'user',
    state: {
        notifications: [],
        admin: []
    },
    actions: {
        init({commit, rootState}, vue) {
            if (rootState.user.isLoggedIn) {
                vue.axios.get('notifications')
                    .then((res) => {
                        commit('notifications', res.data.notifications);
                        commit('admin', res.data.admin);
                    });
                vue.$echo.private('App.User.' + rootState.user.id)
                    .notification((notification) => {
                        commit('add_notification', notification);
                    });
            }
        },
        delete({commit}, id) {
            window.axios.delete('notification/'+id)
                .then(() => {
                    commit('remove_notification', id);
                })
        },
        mark({commit}, id) {
            window.axios.post('notification/'+id)
                .then(() => {
                    commit('mark', id);
                });
        },
        markAll({commit}) {
            return new Promise((resolve) => {
                window.axios.post('notifications/read')
                    .then((res) => {
                        commit('update', res.data);
                        resolve(res.data);
                    });
            });
        }
    },
    mutations: {
        add_notification(state, notification) {
            state.notifications.unshift(notification);
        },
        notifications(state, payload) {
            state.notifications = payload;
        },
        admin(state, payload) {
            state.admin = payload;
        },
        remove_notification(state, id) {
            state.notifications = _.filter(state.notifications, (n) => {
                return n.id !== id;
            })
        },
        mark(state, id) {
            _.each(state.notifications, (n) => {
                if (n.id === id) {
                    n.read_at = true;
                }
            })
        },
        update(state, payload) {
            state.notifications = payload;
        }
    },
    getters: {
        number: state => {
            return _.filter(state.notifications, (n) => {
                return !n.read_at;
            }).length;
        }
    }
}
