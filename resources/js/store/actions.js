export default {

    // set the action to new lead addition or updating existing lead
    set_action({ commit }, payload) {
        commit('set_leads_action', payload);
    },

    // sets the leads for the current page with the payload which is api url
    set_leads({ commit }, payload ) {
        fetch(payload)
        .then( res => res.json() )
        .then( res => {
            commit('set_leads', res);
        })
    },

    // add new lead to the leads object in store
    add_lead({ commit }, payload) {
        commit('add_lead', payload);
    },

    // delete existing lead from the leads object in store
    delete_lead({ commit }, payload) {
        commit('delete_lead', payload);
    },

    // sets the update flag in the store to true or false
    leads_updated({ commit }) {
        commit('leads_updated');
    },

    // set the lead for the selected lead
    set_lead({ commit }, payload ) {
        commit('set_lead', payload);
    },

    // set the query word
    set_query({ commit }, payload) {
        commit('set_query', payload);
    },

    // set the loading to true or false
    set_loading({ commit }, payload) {
        commit('set_loading', payload);
    },

    // set the loading to true or false for info modal box
    set_loading_info({ commit }, payload) {
        commit('set_loading_info', payload);
    },

    // fetch and set the followups for the selected lead for info modal box
    set_followups({ commit }, payload) {
        fetch('/api/followups/' + payload)
        .then( res => res.json() )
        .then( res => {
            commit('set_followups', res.data);
        })
    },

    // fetch the clients and set in the store
    set_clients({ commit }, payload) {
        commit('set_clients', payload);
    },

    // add a new client to the existing clients in the store
    add_client({ commit }, payload) {
        commit('add_client', payload);
    }
}
