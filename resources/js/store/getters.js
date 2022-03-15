export default {
    // get the action which would be new lead addition or updating existing lead
    leads_action: state => { return state.leads_action },

    // get the leads and meta that is set in the store
    leads: state => { return state.leads.data },
    leads_meta: state => { return state.leads.meta },

    // get the lead that is selected
    lead: state => { return state.lead },

    // get the updated flag
    leads_updated: state => { return state.leads.updated },

    // get the query word
    query: state => { return state.query },

    // get the followups for the selected lead
    followups: state => { return state.followups },

    // get the loading which would be true or false 
    get_loading: state => { return state.loading },

    // get the loading which would be true or false for the info of selected lead
    get_loading_info: state => { return state.loading_info },

    // get the status of the lead
    get_status(status) {
        if(status == 'first_call') return 'First Call';
        else if (status == 'followup') return 'Followup';
        else if (status == 'demo') return 'Demo';
        else if (status == 'training') return 'Training';
        else if (status == 'sales') return 'Sold';
    },

    // get the clients from the store
    get_clients: state => { return state.clients },
}