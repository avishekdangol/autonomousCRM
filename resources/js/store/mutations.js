export default {

    // set the action to new lead addition or updating existing lead
    set_leads_action(state, payload) {
        state.leads_action = payload;
    },

    // sets the leads for the current page with the payload which is api url
    set_leads(state, payload) {
        state.leads.data = payload.data;
        state.leads.prevPage = payload.links.prev;
        state.leads.nextPage = payload.links.next;
        state.leads.currentPage = payload.meta.current_page;
        state.leads.perPage = payload.meta.per_page;
        state.leads.total = payload.meta.total;
    },

    // add new lead to the leads object in store
    add_lead(state, payload) {
        state.leads.data.push(payload);
    },

    // delete existing lead from the leads object in store
    delete_lead(state, payload) {
        let leadToRemove = state.leads.data.indexOf(payload);
        state.leads.data.splice(leadToRemove, 1);
    },

    // sets the update flag in the store to true or false
    leads_updated(state,) {
        state.leads.updated++;
    },

    // set the lead for the selected lead
    set_lead(state, payload) {
        state.lead = payload;
    },

    // set the query word
    set_query(state, payload) {
        state.query = payload;
    },

    // set the loading to true or false
    set_loading(state, payload) {
        state.loading = payload;
    },

    // set the loading to true or false for info modal box
    set_loading_info(state, payload) {
        state.loading_info = payload;
    },

    // set the followups for the selected lead for info modal box
    set_followups(state, payload) {
        state.followups = payload;
    },

    // fetch the clients and set in the store
    set_clients(state, payload) {
        state.clients = payload;
    },

    // add a new client to the existing clients in the store
    add_client(state, payload) {
        state.clients.push(payload);
    }
}

