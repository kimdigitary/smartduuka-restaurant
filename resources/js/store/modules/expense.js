import axios from 'axios'
import appService from "../../services/appService";

export const expense = {
    namespaced: true,
    state: {
        lists: [],
        page: {},
        pagination: [],
        show: {},
        temp: {
            temp_id: null,
            isEditing: false,
        },
        simpleList: []
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        show: function (state) {
            return state.show;
        },
        pagination: function (state) {
            return state.pagination
        },
        page: function (state) {
            return state.page;
        },
        temp: function (state) {
            return state.temp;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/expenses';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('lists', res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = '/admin/expenses';
                if (this.state['expense'].temp.isEditing) {
                    // method = axios.put;
                    payload.form.append('_method', 'PUT')
                    url = `/admin/expenses/${this.state['expense'].temp.temp_id}`;
                }
                method(url, payload.form).then(res => {
                    context.dispatch('lists', payload.search).then().catch();
                    context.commit('reset');
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        edit: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/expenses/${payload}`).then((res) => {
                    context.commit('edit', res.data.data);
                    context.commit('temp', payload);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                })
            })
        },
        payment: function (context, payload) {
            context.commit("temp", payload);
        },
        addPayment: function (context, payload) {
            return new Promise((resolve, reject) => {
                let method = axios.post;
                let url = `admin/expense-payments`;
                method(url, payload.form).then(res => {
                    context.dispatch('lists', {vuex: true}).then().catch();
                    context.commit('reset');
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            })
        },
        viewPayment: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/purchase/payment/${this.state['purchase'].temp.temp_id}`).then((res) => {
                    context.commit('viewPayment', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                })
            })
        },
        paymentDestroy: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/purchase/payment/${payload.purchase_id}/${payload.id}`).then((res) => {
                    context.dispatch("lists", payload.search).then().catch();
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        destroy: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.delete(`admin/expenses/${payload.id}`).then((res) => {
                    context.dispatch('lists', payload.search).then().catch();
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        // api/admin/expenses/{expense}
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/expenses/${payload}`).then((res) => {
                    context.commit('show', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        reset: function (context) {
            context.commit('reset');
        },
        export: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/expense-categories-export';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url, {responseType: 'blob'}).then((res) => {
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload
        },
        show: function (state, payload) {
            state.show = payload;
        },
        temp: function (state, payload) {
            state.temp.temp_id = payload;
            state.temp.isEditing = true;
        },
        reset: function (state) {
            state.temp.temp_id = null;
            state.temp.isEditing = false;
        },
        edit: function (state, payload) {
            state.edit = payload;
        },
    },
}
