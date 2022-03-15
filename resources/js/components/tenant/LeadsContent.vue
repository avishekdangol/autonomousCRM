<template>
    <div>
        <h3 class="heading">{{ page.title }}</h3>
        <div class="d-flex mx-1" v-if="page.title.toLowerCase() != 'sales'">
            <div class="box-interested">
                <p class="mx-3">Interested</p>
            </div>
            <div class="box-not-interested">
                <p class="mx-3">Not-Interested</p>
            </div>
        </div>
        <div class="table-wrapper">
        
            <table class="table" id="all-leads">
                <thead>
                    <tr>
                        <th width="360px" class="font-weight-bold mr-4">Organization's Name</th>
                        <th width="240px" class="font-weight-bold mr-4">Address</th>
                        <th width="180px" class="font-weight-bold mr-4">Contact Person</th>
                        <th width="180px" class="font-weight-bold mr-4">Phone Number</th>
                        <th width="180px" v-if="admin == 1" class="font-weight-bold mr-4">Followup By</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-if="loading">
                        <td colspan="5">
                            <div class="text-center my-4 spinner-wrap">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </td>
                    </tr>                  

                    <tr class="lead-item" 
                        v-for="lead, index in leads" 
                        :key="lead.id" v-else
                        :class=" page.title.toLowerCase() !== 'sales' ? lead.quality : ''">

                        <td width="360px">
                            <p class="mb-0">{{ lead.name }}</p>
                            <p class="small options my-0">
                                <span @click="get_lead(index, 'info')" v-b-modal.infomodal>Info</span> | 
                                <span @click="get_lead(index)" v-b-modal.leadsmodal>Update</span> | 
                                <span class="trash" @click="get_lead(index)" v-b-modal.deleteleads>Delete</span></p>
                        </td>
                        <td width="240px">{{ lead.address }}</td>
                        <td width="180px">{{ lead.person }}</td>
                        <td width="180px">{{ lead.phone }}</td>
                        <td width="180px" v-if="admin == 1">{{ lead.followup_by }}</td>
                    </tr>

                    <tr v-if="!leads.length && !loading">
                        <td colspan="7">{{ page.empty }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav aria-label="Page navigation" v-if="meta.total > meta.perPage">
            <ul class="pagination justify-content-center">
                <li class="page-item mx-1">
                <button class="page-link" @click="fetch_data( meta.prevPage )" aria-label="Previous" :disabled="meta.prevPage == null">
                    <span aria-hidden="true">Previous</span>
                    <span class="sr-only">Previous</span>
                </button>
                </li>            

                <li class="page-item mx-1">
                <button class="page-link" @click="fetch_data( meta.nextPage )" aria-label="Next" :disabled="meta.nextPage == null">
                    <span aria-hidden="true">Next</span>
                    <span class="sr-only">Next</span>
                </button>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default ({
    data() {
        return {
            admin: this.$admin,
            userID: this.$user_id,
            tenantID: this.$tenant_id,

            lead: {},

            page: {
                title: '',
                api: '',
                empty: '',
            },

            query_word: ''
        }
    },

    computed: {
        ...mapGetters({
            leads: 'leads',
            updated: 'leads_updated',
            meta: 'leads_meta',
            query: 'query',
            loading: 'get_loading',
        }),

        check_query() {
            return this.query;
        },

        check_leads() {
            return this.leads.length;
        },

        check_updated() {
            return this.leads_updated;
        }
    },

    watch: {

        // listen to new queries
        check_query(newValue) {
            this.page.api = this.admin == 1 ? '/api/query/' + newValue : '/api/query/' + this.userID + '/' + newValue;
            
            this.fetch_data();
            setTimeout( () => {
                this.query_word = newValue;
                this.set_page();
            }, 250);
        },

        // check if there is any new leads added
        check_leads() {
            this.fetch_data();
        },

        // check if any lead is updated
        check_updated() {
            this.fetch_data();
        }
    },

    async mounted() {
        if(this.$route.path == '/query') {
            this.query_word = this.query;
            this.set_query(window.location.search.split('=')[1]);
        } 

        this.set_page();
        this.set_loading(true);
        await this.fetch_data();
        setTimeout( () => {
            this.set_loading(false);
        }, 800);

        const private_channel = "tenant.leads." + this.tenantID;

        Echo.private(private_channel)
        .listen('Leads', () => {
            this.fetch_data();
        })
    },

    methods: {
        ...mapActions(['set_leads', 'set_lead', 'leads_updated', 'set_action', 'set_followups', 'set_loading', 'set_loading_info', 'set_query']),

        set_page() {
            if(this.$route.path == '/l') {
                this.page.title = 'New Leads for Today';
                this.page.empty = 'There are no leads for today.';
                this.page.api = this.admin == 1 ? '/api/first_call/today' : '/api/first_call/today/' + this.userID;
            } 

            else if(this.$route.path == '/l/followup') {
                this.page.title = 'Followup Leads for Today';
                this.page.empty = 'There are no followup leads for today.';
                this.page.api = this.admin == 1 ? '/api/followup/today' : '/api/followup/today/' + this.userID;
            }

            else if(this.$route.path == '/l/demo') {
                this.page.title = 'Pending Demo for Today';
                this.page.empty = 'There are no pending demo for today.';
                this.page.api = this.admin == 1 ? '/api/demo/today' : '/api/demo/today/' + this.userID;
            } 

            else if(this.$route.path == '/l/training') {
                this.page.title = 'Pending Trainings for Today';
                this.page.empty = 'There are no pending trainings for today.';
                this.page.api = this.admin == 1 ? '/api/training/today' : '/api/training/today/' + this.userID;
            }
            
            else if(this.$route.path == '/a') {
                this.page.title = 'All Time New Leads'
                this.page.empty = 'There are no leads yet.';
                this.page.api = this.admin == 1 ? '/api/first_call/all' : '/api/first_call/all/' + this.userID;
            } 
            
            else if(this.$route.path == '/a/followup') {
                this.page.title = 'All Time Followups';
                this.page.empty = 'There are no followup leads for today.';
                this.page.api = this.admin == 1 ? '/api/followup/all' : '/api/followup/all/' + this.userID;
            }

            else if(this.$route.path == '/a/demo') {
                this.page.title = 'All Time Demo';
                this.page.empty = 'There are no pending demo for you yet.';
                this.page.api = this.admin == 1 ? '/api/demo/all' : '/api/demo/all/' + this.userID;
            }

            else if(this.$route.path == '/a/training') {
                this.page.title = 'All Time Trainings';
                this.page.empty = 'There are no pending trainings for today.';
                this.page.api = this.admin == 1 ? '/api/training/all' : '/api/training/all/' + this.userID;
            }

            else if(this.$route.path == '/a/all') {
                this.page.title = 'All Time Leads';
                this.page.empty = 'There are no leads for you yet.';
                this.page.api = this.admin == 1 ? '/api/all' : '/api/all/' + this.userID;
            } else if(this.$route.path == '/query') {
                this.page.title = 'Search Results for "' + this.query_word + '"';
                this.page.empty = 'No result found for ' + this.query_word + '.';
                this.page.api = this.admin == 1 ? '/api/query/' + this.query : '/api/query/' + this.userID + '/' + this.query; 
            }

            else if(this.$route.path == '/sales') {
                this.page.title = 'Sales';
                this.page.empty = this.admin == 1 ? 'There hasn\'t been any sales yet.' : 'You haven\'t made any sales yet.';
                this.page.api = this.admin == 1 ? '/api/sales/all' : '/api/sales/all/' + this.userID;
            }
        },

        async fetch_data(url = this.page.api) {
            await this.set_leads(url);
        },

        async get_lead(index, btn = '') {
            if (btn === 'info') {
                this.set_loading_info(true);
                await this.set_followups(this.leads[index].id);
                
                setTimeout( () => {
                    this.set_loading_info(false);
                }, 800)
            }
            
            this.set_action('edit');
            this.set_lead(this.leads[index]);
        }
    },
})
</script>

<style>
    .table td {
        height: 62px;
    }

    .table .options {
        display: none;
    }

    .table tr:hover .options {
        display: block;
    }

    .table .options span:hover {
        text-decoration: underline;
    }

    .trash {
        color: #df4444;
    }

    
</style>

<style scoped>
    .lead-item {
        transition: all 0.2s ease-in;
    }

    .page-link {
        background-color: rgb(52, 144, 220);
        color: #fff;
        cursor: pointer;
        border: none;
    }

    .page-link:hover {
        background-color: #217bc4;
        color: #fff;
        border: none;
    }

    .page-link:disabled {
        background-color: rgb(52, 144, 220, 0.8);
        cursor: default;
        border: none;
    }
</style>
