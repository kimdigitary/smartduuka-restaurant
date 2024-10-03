import axios from 'axios'
import appService from "../../services/appService";


export const user = {
    namespaced: true,
    state: {
        lists: [],
        diningTable: [],
        paymentMethods: [],
        page: {},
        pagination: [],
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        diningTable: function (state) {
            return state.diningTable;
        },
        paymentMethods: function (state) {
            return state.paymentMethods;
        },

        pagination: function (state) {
            return state.pagination
        },
        page: function (state) {
            return state.page;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/users';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('lists', res.data.data);
                        context.commit('page', res.data.meta);
                        context.commit('pagination', res.data);
                    }

                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        diningTableList: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/dining-table/all';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('diningTableList', res.data.data);
                        context.commit('page', res.data.meta);
                        context.commit('pagination', res.data);
                    }

                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        paymentMethodsList: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'admin/purchase/payment-methods';
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit('paymentMethodsList', res.data.data);
                        context.commit('page', res.data.meta);
                        context.commit('pagination', res.data);
                    }

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
        diningTableList: function (state, payload) {
            state.diningTable = payload
        },
        paymentMethodsList: function (state, payload) {
            state.paymentMethods = payload
        },
        pagination: function (state, payload) {
            state.pagination = payload;
        },
        page: function (state, payload) {
            if (typeof payload !== "undefined" && payload !== null) {
                state.page = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total
                }
            }
        },
    },
}
