import Vue from 'vue'
import vuex from 'vuex'
Vue.use(vuex);

import actions from './actions.js';
import mutations from './mutations.js';
import getters from './getters.js';

const store = new vuex.Store({
    state: {
       leads_action: 'new',
       leads: {
           data: {},
           meta: {
                prevPage: '',
                nextPage: '',
                currentPage: '',
                total: '',
                perPage: '',
           },
           updated: 0,
       },
       lead: {},

       clients: [],

       admin: 1,
       query: '',
       page: '',
       followups: {},
       loading: false,
       loading_info: false,
    },
    getters,
    actions,
    mutations,
})

export default store;