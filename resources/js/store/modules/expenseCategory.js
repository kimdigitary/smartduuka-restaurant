import axios from 'axios'
import appService from "../../services/appService";


export const expenseCategory = {
    namespaced: true,
    state: {
        lists: [],
        page: {},
        pagination: [],
        depthTrees: [],
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
        depthTrees: function (state) {
            return state.depthTrees;
        },
    },
    actions: {
        depthTrees: function (context) {
            return new Promise((resolve, reject) => {
                axios.get('admin/expense-category/depth-tree').then((res) => {
                    context.commit('depthTrees', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = 'expense-categories';
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
                let url = '/expense-categories';
                if (this.state['expenseCategory'].temp.isEditing) {
                    // method = axios.put;
                    payload.form.append('_method', 'PUT')
                    url = `/expense-categories/${this.state['expenseCategory'].temp.temp_id}`;
                }
                payload.form.user_id = this.state['auth'].authInfo.id;
                console.log(payload.form)
                method(url, payload.form).then(res => {
                    context.dispatch('lists', payload.search).then().catch();
                    context.dispatch('depthTrees', payload.search).then().catch();
                    context.commit('reset');
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        edit: function (context, payload) {
            context.commit('temp', payload);
        },
        destroy: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.delete(`expense-categories/${payload.id}`).then((res) => {
                    context.dispatch('lists', payload.search).then().catch();
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/product/show/${payload}`).then((res) => {
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
                let url = 'expense-categories-export';
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
        depthTrees: function (state, payload) {
            state.depthTrees = payload;
        },
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
    },
}
