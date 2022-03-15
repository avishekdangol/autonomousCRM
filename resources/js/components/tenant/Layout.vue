<template>
    <div>
        <!-- <span class="mobile-only fa fa-user" @click="sidebar"></span> -->
        <div class="row">
            <div class="col-md-2 col-sm-12">

                <!-- NAVIGATION MENU -->
                <div class="menu">

                    <!-- DASHBOARD AND SEARCH BAR -->
                    <ul class="menu-link">
                        <li class="h-50">
                            <div class="dashboard">
                                <router-link class="nav-link p-0" :to="{ name: 'TenantDashboard' }" exact>
                                    <h6 class="py-4 dash"><i class="fa fa-tachometer-alt"></i> Dashboard</h6>
                                </router-link>
                                <i class="fa fa-search p-4 text-white search-toggle" @click="search_toggle"></i>
                            </div>

                            <div id="search-div">
                                <div class="form-floating d-flex">
                                    <input type="text" class="form-control" id="search" name="search" placeholder="Search" v-model="search" @keyup.enter="query">
                                    <label for="search">Search</label>
                                    <div class="buttons d-flex hidden">
                                        <a class="btn search-btn px-3" active-class="active" @click="query"
                                            :class="{ disabled: !search.length}"
                                        >
                                            <i class="fa fa-search"></i>
                                        </a>

                                        <a class="btn px-3" @click="search_toggle"><i class="fa fa-times"></i></a>
                                    </div>
                                    
                                </div>
                            </div>
                        </li>
                    </ul>

                    
                    <h6 class="py-3" @click="set_action('new')" v-b-modal.leadsmodal><i class="fa fa-plus"></i>&nbsp; Add New Lead</h6>
                    
                    <h6 class="py-3" @click="toggle"><i class="fa fa-calendar-day"></i> Today's Tasks</h6>
                    <ul class="menu-link hidden" ref="today">
                        <li><router-link class="nav-link" :to="{ path: '/l' }" exact>New Leads</router-link></li>
                        <li><router-link class="nav-link" :to="{ path: '/l/followup' }" exact>Followup Leads</router-link></li>
                        <li><router-link class="nav-link" to="/l/demo" exact>Demo</router-link></li>
                        <li><router-link class="nav-link" to="/l/training" exact>Training</router-link></li>
                    </ul>

                    <h6 class="py-3" @click="toggle"><i class="fa fa-calendar-days"></i> All Time Leads</h6>
                    <ul class="menu-link hidden" ref="all">
                        <li><router-link class="nav-link" :to="{ path: '/a' }" exact>New Leads</router-link></li>
                        <li><router-link class="nav-link" :to="{ path: '/a/followup' }" exact>Followup Leads</router-link></li>
                        <li><router-link class="nav-link" :to="{ path: '/a/demo' }" exact>Demo</router-link></li>
                        <li><router-link class="nav-link" :to="{ path: '/a/training' }" exact>Training</router-link></li>
                        <li><router-link class="nav-link" :to="{ path: '/a/all' }" exact>All</router-link></li>
                    </ul>

                    <ul class="menu-link">
                        <li>
                            <router-link class="nav-link p-0" :to="{ name: 'Sales' }">
                                <h6 class="py-3"><i class="fa fa-bag-shopping"></i> Sales</h6>
                            </router-link>
                        </li>
                        
                        <li>
                            <router-link class="nav-link p-0" :to="{ name: 'Users' }" v-if="admin == 1" exact>
                                <h6 class="py-3"><i class="fa fa-user-group"></i> Users</h6>
                            </router-link>
                        </li>
                    </ul>

                    
                </div>
            </div>

            <div class="col-md-9 py-4">
                <router-view :key="$route.path" />
            </div>
        </div>
        
        <!-- LEADS FORM MODAL -->
        <b-modal id="leadsmodal" 
          :title="action == 'new' ? 'Add New Lead' : lead.name" 
          :ok-title="action == 'new' ? 'Submit' : 'Update'" 
          @ok="submit" centered>
            <div>
                <div class="text-muted">* Required Fields</div>                
            </div>
            <b-form class="needs-validation">
                <div class="form-floating my-2">
                    <input type="text" name="name" ref="name" class="form-control" id="name" placeholder="Organization's Name" v-model="data.name">
                    <label for="floatingInput">Organization's Name *</label>   
                    <div class="invalid-feedback">{{ errors.name }}</div>         
                </div>

                <div class="form-floating my-2">
                    <input type="text" name="address" ref="address" class="form-control" id="address" placeholder="Address" v-model="data.address">
                    <label for="floatingInput">Address *</label>
                    <div class="invalid-feedback">{{ errors.address }}</div>
                </div>

                <div class="form-floating my-2">
                    <input type="text" name="person" ref="person" class="form-control" id="person" placeholder="Contact Person" v-model="data.person">
                    <label for="floatingInput">Contact Person *</label>
                    <div class="invalid-feedback">{{ errors.person }}</div>
                </div>

                <div class="form-floating my-2">
                    <input type="email" name="email" ref="email" class="form-control" id="email" placeholder="Email Address" v-model="data.email">
                    <label for="floatingInput">Email Address</label>
                    <div class="invalid-feedback">{{ errors.email }}</div>
                </div>

                <div class="form-floating my-2">
                    <input type="tel" name="phone" ref="phone" class="form-control" id="phone" placeholder="Phone Number" v-model="data.phone">
                    <span class="phone-prefix">+977 - </span>
                    <label for="floatingInput">Phone Number *</label>
                    <div class="invalid-feedback">{{ errors.phone }}</div>
                </div>

                <div class="form-floating my-2">
                    <input type="text" name="source" ref="source" class="form-control" id="source" placeholder="Source" v-model="data.source">
                    <label for="floatingInput">Source</label>
                    <div class="invalid-feedback">{{ errors.source }}</div>
                </div>

                <div class="form-floating my-2">
                    <select class="form-select" name="quality" ref="quality" id="quality" v-model="data.quality">
                        <option selected disabled value="null">Select Lead's Quality</option>
                        <option value="Interested">Interested</option>
                        <option value="Not-Interested">Not-Interested</option>
                    </select>
                    <label for="floatingInput">Lead's Quality *</label>
                    <div class="invalid-feedback">{{ errors.quality }}</div>
                </div>

                <div class="form-floating my-2">
                    <select class="form-select" name="followup_by" ref="followup_by" id="followup_by" v-model="data.followup_by">
                        <option selected disabled value="null">Select Followup Staff</option>
                        <option v-for="user in users" :key="user.id">
                            {{ user.name }}
                        </option>
                    </select>
                    <label for="floatingInput">Select Followup Staff *</label>
                    <div class="invalid-feedback">{{ errors.followup_by }}</div>
                </div>

                <div class="form-floating my-2">
                    <input type="date" name="followup_date" ref="followup_date" class="form-control" id="followup_date" 
                        :placeholder="action == 'new' ? 'Followup Date' : 'Latest Followup Date'" 
                        :min="today" 
                        v-model="data.followup_date"
                        :disabled="action == 'edit'"
                    >
                    <label for="floatingInput" v-if="action == 'new'">Followup Date *</label>
                    <label for="floatingInput" v-if="action == 'edit'">Latest Followup Date *</label>
                    <div class="invalid-feedback">{{ errors.followup_date }}</div>
                </div>

                <div class="form-floating my-2" v-if="action == 'edit'">
                    <input type="date" ref="last_followup_date" name="last_followup_date" class="form-control" id="last_followup_date" :min="today" placeholder="Add Next Followup Date" v-model="data.last_followup_date">
                    <label for="floatingInput">Add Next Followup Date</label>
                    <div class="invalid-feedback">{{ errors.last_followup_date }}</div>
                </div>

                <div class="form-floating my-2" v-if="action == 'edit'">
                    <select class="form-select" name="status" ref="status" id="status" v-model="data.status">
                        <option selected value="first_call">First Call</option>
                        <option value="followup">Followup</option>
                        <option value="demo">Demo</option>
                        <option value="training">Training</option>
                        <option value="sales">Sales</option>                        
                    </select>
                    <label for="floatingInput">Status</label>
                    <div class="invalid-feedback">{{ errors.status }}</div>
                </div>

                <div class="form-floating">
                    <textarea class="form-control" ref="remarks" placeholder="Remarks" id="floatingTextarea" v-model="data.remarks"></textarea>
                    <label for="floatingTextarea">Remarks</label>
                    <div class="invalid-feedback">{{ errors.remarks }}</div>
                </div>

                
            </b-form>
        </b-modal>

        <!-- DELETE LEADS MODAL -->
        <b-modal id="deleteleads" :title="data.name" ok-title="Delete" ok-variant="danger" @ok="trash">
            <h6 class="fw-bold">Are you sure you want to delete {{ data.name }}'s lead?</h6>
        </b-modal>

        <!-- INFO MODAL -->
        <b-modal id="infomodal" class="py-4" size="lg"  hide-footer hide-header>
            <div class="modal-header p-0">
                <div class="mb-2">
                    <h5 class="modal-title my-0">{{ data.name }}</h5>
                    <p class="my-0">{{ data.address }}</p>
                </div>
                <button class="close" aria-label="close" type="button" @click="hide('infomodal')">&times;</button>
            </div>
            <div class="row mt-3 mx-1">
                <div class="col">
                    <h5 class="mb-1">Lead's Details</h5>
                    <p class="my-0 px-4" v-if="data.source"><span class="fw-bold">Leads Source:</span> {{ data.source }}</p>
                    <p class="my-0 px-4"><span class="fw-bold">Lead's Quality:</span> {{ data.quality }}</p>
                    <p class="my-0 px-4"><span class="fw-bold">Status:</span> {{ get_status(data.status) }}</p>
                    <p v-if="data.remarks" class="my-0 px-4"><span class="fw-bold">Remarks:</span> {{ data.remarks}} </p>
                </div>

                <div class="col">
                    <h5 class="mb-1">Contact Details</h5>
                    <p class="my-0 px-4"><span class="fw-bold">Contact Person:</span> {{ data.person }}</p>
                    <p class="my-0 px-4"><span class="fw-bold">Phone Number:</span> {{ data.phone }}</p>
                    <p class="my-0 px-4" v-if="data.email"><span class="fw-bold">Email Address:</span> {{ data.email }}</p>
                </div>
            </div>

            <div class="container my-4">
                <h5>Call Details</h5>
                <table class="table" width="100%">
                    <thead>
                        <tr>
                            <th width="50%">Date</th>
                            <th width="50%">Purpose</th>
                        </tr>
                    </thead>

                    <tbody v-if="loading_info == false">
                        <tr v-for="followup in followups" :key="followup.id">
                            <td width="50%">{{ followup.followup_date }}</td>
                            <td width="50%">{{ get_status(followup.status) }}</td>
                        </tr>

                        <tr v-if="!followups.length">
                            <td colspan="2">There are no call details yet.</td>
                        </tr>
                    </tbody>

                    <tr v-else class="text-center mx-auto">
                        <td colspan="2">
                            <img class="loading-icon" src="/assets/logo.gif" alt="">
                        </td>
                    </tr>
                </table>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import { mapActions, mapGetters } from 'vuex';

    export default {
        data() {
            return {
                users: {},
                admin: this.$admin,
                data: {
                    id: '',
                    name: '',
                    address: '',
                    person: '',
                    phone: '',
                    email: '',
                    source: '',
                    quality: null,
                    followup_by: null,
                    followup_date: null,
                    last_followup_date: null,
                    status: '',
                    remarks: '',
                },
                search: '',
                today: Vue.moment().format('YYYY-MM-DD'),

                errors: {},
                counts: this.count,
            }
        },

        computed: {
            ...mapGetters({
                'action': 'leads_action',
                'lead': 'lead',
                'followups': 'followups',
                'loading': 'get_loading',
                'loading_info': 'get_loading_info'
            }),

            check_action() {
                return this.action;
            },

            check_lead() {
                return this.lead;
            },

        },

        watch: {
            check_action(newValue) {
                if(newValue == 'edit') this.set_lead();
                else this.reset_lead();
            },

            check_lead(newValue) {
                if(newValue) this.set_lead();
                else this.reset_lead();
            },
        },

        mounted() {
            this.get_users();

            const pages = {
                'today': /^\/l/,
                'all': /^\/a/
            };

            Object.entries(pages).forEach(page => {
                if(page[1].test(window.location.pathname)) {
                    this.$refs[page[0]].classList.remove('hidden');
                }
            }) 
        },

        methods: {
            ...mapActions({
                set_action: 'set_action',
                set_query: 'set_query',
                add_lead: 'add_lead',
                leads_updated: 'leads_updated',
                delete_lead: 'delete_lead',
                set_leads: 'set_leads',
            }),

            sidebar() {
                document.querySelector('.menu').style.width = '100%';
            },

            toggle(e) {
                const target = e.currentTarget.nextElementSibling;
                target.classList.toggle('hidden');
            },

            query() {
                if (this.search == window.location.search.split('=')[1]) return;
                else if (this.search == '') return;

                this.set_query(this.search);
                let path = '/query';
                if (this.$route.path !== path) this.$router.push({ path: path, query: {'query': this.search }});
                else this.$router.replace({ query: { 'query': this.search }})
                this.search = '';
            },

            trash() {

                // pass the id of the lead to be deleted
                axios.get('/api/leads/' + this.lead.id) 
                .then( response => {
                    if (response) {

                        // delete from the store
                        this.delete_lead(this.lead);

                        // create an event to listen in real time
                        axios.post('/api/leads/event', this.data)

                        // hide the modals
                        this.$bvModal.hide('deleteleads');
                        this.$bvModal.hide('leadsmodal')

                        this.$swal({
                            title: 'Successfully Deleted!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        // create an event to listen in real time
                        // axios.post('/api/leads/event', this.lead)
                        // .then ( response => {
                        // }) 
                        
                    } else {

                        // show error dialog box
                        this.$swal({
                            title: 'Something went wrong!',
                            icon: 'error',
                        })
                    }
                })
            },

            async get_users() {
               await fetch('/api/users/all', {
                    headers : { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then( res => res.json() )
                .then( res => {
                   this.users = res.data;
               })
            },

            submit(bvModalEvt) {
                bvModalEvt.preventDefault();
                this.errors = {};

                if(this.validate()) {
                    if (this.action == 'new') this.create();
                    else this.update();
                } 
            },

            validate() {
                this.reset_invalid_class();

                if (!this.data.name.length) {
                    this.errors.name = "Please enter the name of the Organization.";
                    this.$refs.name.classList.add('is-invalid');
                } else this.$refs.name.classList.add('is-valid');

                if (!this.data.address.length) {
                    this.errors.address = "Please enter the address of the Organization.";
                    this.$refs.address.classList.add('is-invalid');
                } else this.$refs.address.classList.add('is-valid');

                if (!this.data.person.length) {
                    this.errors.person = "Please enter the name of the Contact Person.";
                    this.$refs.person.classList.add('is-invalid');
                } else this.$refs.person.classList.add('is-valid');
                
                if (this.data.email) {
                    if (!this.validEmail(this.data.email)) {
                        this.errors.email = "Please enter a valid email address.";
                        this.$refs.email.classList.add('is-invalid');
                    } else this.$refs.email.classList.add('is-valid');
                }

                if (!this.data.phone.length) {
                    this.errors.phone = "Please enter the phone number of the Contact Person.";
                    this.$refs.phone.classList.add('is-invalid');
                } else if (!this.validPhone(this.data.phone)) {
                    this.errors.phone = "Please enter valid phone number";
                    this.$refs.phone.classList.add('is-invalid');
                }
                else this.$refs.phone.classList.add('is-valid');

                if (this.data.quality == null) {
                    this.errors.quality = "Please select the quality of the lead.";
                    this.$refs.quality.classList.add('is-invalid');
                } else this.$refs.quality.classList.add('is-valid');

                if (this.data.followup_by == null) {
                    this.errors.followup_by = "Please select the followup staff.";
                    this.$refs.followup_by.classList.add('is-invalid');
                } else this.$refs.followup_by.classList.add('is-valid');

                if (this.data.followup_date == null) {
                    this.errors.followup_date = "Please select the followup date.";
                    this.$refs.followup_date.classList.add('is-invalid');
                } else this.$refs.followup_date.classList.add('is-valid');

                if (!Object.keys(this.errors).length) {
                    this.data.followup_date = Vue.moment(this.data.followup_date).format('YYYY-MM-DD');
                    if (this.action == 'edit' && this.data.last_followup_date) this.data.last_followup_date = Vue.moment(this.data.last_followup_date).format('YYYY-MM-DD');
                    else this.data.last_followup_date = null;
                    return true;
                } else return false;
            },

            validPhone(phone) {
                let re = /^[0-9,-]{9,10}$/;
                return re.test(phone);
            },

            validEmail(email) {
                let re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                return re.test(email);
            },

            create() {

                // Pass the data to create new lead
                axios.post('/api/leads', this.data)
                .then( response => {
                    if (response.data == 'created') {

                        // create an event to listen in real time
                        axios.post('/api/leads/event', this.data)

                        // show created dialog box
                        this.$swal({
                            title: 'Leads Added Successfully!',
                            icon: 'success',
                            showCancelButton: true,
                            cancelButtonText: 'Add Another Lead?',
                            confirmButtonText: 'Go Back',
                        })
                        .then( result => {
                            if (result.isConfirmed) {

                            // hide the create modal
                            this.$bvModal.hide('leadsmodal');
                            }
                        })

                        this.reset_lead();
                        this.reset_valid_class();
                    } else {

                        // show error dialog box

                        this.$swal({
                            icon: 'error',
                            title: 'Something went wrong!',
                        })
                    }
                })
            },

            update() {

                // pass the data to update the selected lead
                axios.post('/api/leads/' + this.lead.id, this.data)
                .then( response => {
                    if (response.status == 200) {

                        // update in the store
                        this.leads_updated();

                        // create an event to listen in real time
                        axios.post('/api/leads/event', this.data)

                        // show updated dialog box
                        this.$swal({
                            title: 'Leads Updated Successfully!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        // hide the update modal
                        this.$bvModal.hide('leadsmodal');
                    } else {

                        // show error dialog box
                        this.$swal({
                            icon: 'error',
                            title: 'Something went wrong!',
                        })
                    }                    
                })
            },

            set_lead() {
                this.data.id = this.lead.id;
                this.data.name = this.lead.name;
                this.data.address = this.lead.address;
                this.data.phone = this.lead.phone;
                this.data.person = this.lead.person;
                this.data.email = this.lead.email;
                this.data.source = this.lead.source;
                this.data.quality = this.lead.quality;
                this.data.followup_by = this.lead.followup_by;
                this.data.followup_date = this.lead.followup_date;
                this.data.last_followup_date = this.lead.last_followup_date;
                this.data.status = this.lead.status;
                this.data.remarks = this.lead.remarks;
            },

            get_status(status) {
                if(status == 'first_call') return 'First Call';
                else if (status == 'followup') return 'Followup';
                else if (status == 'demo') return 'Demo';
                else if (status == 'training') return 'Training';
                else if (status == 'sales') return 'Sold';
            },

            hide(modal) {
                this.$bvModal.hide(modal);
            },

            reset_lead() {
                this.data.id = this.data.name = this.data.address = this.data.person = this.data.phone = this.data.email = this.data.source = this.data.remarks = this.data.status = this.errors = '';
                this.data.quality = this.data.followup_by = this.data.followup_date = this.data.last_followup_date = null;
            },

            reset_invalid_class() {
                Object.values(this.$refs).forEach(value => {
                    if (value != undefined && value.classList.contains('is-invalid')) value.classList.remove('is-invalid');
                });
            },

            reset_valid_class() {
                Object.values(this.$refs).forEach(value => {
                    if (value != undefined && value.classList.contains('is-valid')) value.classList.remove('is-valid');
                });
            },

            search_toggle() {
                const searchBar = document.getElementById('search-div');
                if (searchBar.style.width == '100%') searchBar.style.width = '0';
                else searchBar.style.width = '100%';

                document.querySelector('.buttons').classList.toggle('hidden');
                document.querySelector('.dashboard').classList.toggle('hidden');
            }
        }
    }
</script>

<style>
    .box-interested,
    .box-not-interested {
        position: relative;
    }

    .box-interested p:before {
        position: absolute;
        left: 0;
        top: 5px;
        content: '';
        width: 12px;
        height: 12px;
        border: 1px solid #e6e6e6;
        background-color: rgb(0, 153, 0, 0.2);
    }

    .box-not-interested p:before {
        position: absolute;
        left: 0;
        top: 6px;
        content: '';
        width: 12px;
        height: 12px;
        border: 1px solid #e6e6e6;
        background-color: rgb(153, 0, 0, 0.2);
    }

    .disabled {
        pointer-events: none;
    }

    .table-wrapper {
        min-height: 60vh;
    }

    h5 {
        margin: 0;
        font-weight: 700 !important;
    }

    .close {
        font-size: 24px;
        cursor: pointer;
    }

    .Interested {
        background-color: rgb(0, 153, 0, 0.2);
    }

    .Not-Interested {
        background-color: rgb(153, 0, 0, 0.2);
    }

    #modal p {
        margin-bottom: 0.5rem;
    }
</style>

<style scoped>
    a {
        text-decoration: none;
    }

    .table td {
        height: 18px;
    }

    .loading-icon {
        width: 60px;
        height: auto;
    }
    
    .router-link-active,
    .router-link-exact-active {
        background-color: #0091D5;
    }

    .active {
        background-color: transparent;
    }

    #phone {
        position: relative;   
        padding-left: 52px;
    }

    .phone-prefix {
        opacity: 0;
        position: absolute;
        left: 6px;
        top: 26px;
        z-index: 1;
        transition: all 0.1s ease-in;
    }

    #phone:not(:placeholder-shown) + .phone-prefix {
        opacity: 1;
    }

    #phone:focus + .phone-prefix {
        opacity: 1;
    }

    .trash {
        color: #df4444;
        cursor: pointer;
    }

    .trash:hover {
        color: #e97c7c;
    }

    .trash-text:hover {
        text-decoration: underline;
    }

    /deep/ .modal-title {
        font-weight: bold;
    }

    /deep/ .modal-backdrop {
        background-color: rgba( 51, 51, 51, 0.25 );
        box-shadow: 0 8px 32px 0 rgba( 31, 38, 135, 0.37 );
        backdrop-filter: blur( 4px );
        -webkit-backdrop-filter: blur( 4px );
        border-radius: 10px;
        border: 1px solid rgba( 255, 255, 255, 0.18 );
    }

    /deep/ .modal .close {
        border: none;
        background-color: transparent;
        font-size: 24px;
    }

    /deep/ .modal .btn {
        color: #f0f0f0;
    }

    /deep/ #floatingTextarea {
        height: 120px;
    }
</style>