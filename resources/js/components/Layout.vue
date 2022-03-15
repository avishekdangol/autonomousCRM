<template>
    <div class="row">

        <!-- Sidebar Wrapper-->
        <div class="col-md-2 col-sm-12">

            <!-- NAVIGATION MENU -->
            <div class="menu">

                <!-- Menu Contents -->
                <ul class="menu-link">

                    <li class="h-50">

                        <!-- DASHBOARD AND SEARCH BAR -->
                        <div class="dashboard">
                            <router-link class="nav-link p-0" :to="{ name: 'dashboard' }" exact>
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

                    <li>
                        <h6 class="py-3" v-b-modal.newClient><i class="fa fa-plus"></i>&nbsp; Add New Client</h6>
                    </li>

                    <li>
                        <router-link class="nav-link p-0" :to="{ name: 'clients' }"><h6 class="py-3"><i class="fa fa-user-group"></i>&nbsp; Clients</h6></router-link>
                    </li>

                    <li>
                        <router-link class="nav-link p-0" :to="{ name: 'licenses' }"><h6 class="py-3"><i class="fa fa-id-card"></i>&nbsp; Licenses</h6></router-link>
                    </li>
                </ul>              
                                    
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 py-4">
            <router-view :key="$route.path" />
        </div>

        <!-- Modals -->

        <!-- ADD NEW CLIENT -->
        <b-modal id="newClient" title="ADD NEW CLIENT" ok-title="Register" @ok="register">
            <div>
                <div class="text-muted">* Required Fields</div>                
            </div>
            <b-form class="needs-validation">
                <div class="form-floating my-2">
                    <input type="text" name="name" ref="name" class="form-control" id="name" placeholder="Organization's Name" v-model="data.name">
                    <label for="floatingInput">Organization's Name *</label>   
                    <div class="invalid-feedback">{{ errors.name }}</div>         
                </div>

                <div class="my-2">
                    <div class="d-flex domain">
                        <div class="form-floating w-75">
                            <input type="text" name="domain" ref="domain" class="form-control" id="domain" placeholder="Organization's Domain" v-model="data.domain">
                            <label for="floatingInput">Organization's Domain*</label>
                            <div class="invalid-feedback">{{ errors.domain }}</div>
                            <span class="domain-exists text-danger small hidden">This domain already exists. Please enter another domain.</span>
                        </div>

                        <span class="domain-suffix">.{{ base_url }}</span>
                    </div>
                </div>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
import axios from 'axios';
import { mapActions } from 'vuex';
export default {
    data() {
        return {
            data: {
                name: '',
                domain: '',
            },
            errors: {},
            domain_validity: false,

            search: '',
            base_url: process.env.MIX_APP_BASE_URL,
        }
    },

    computed: {
        // set the name computed property to watch for any changes in 'name' field
        name() {
            return this.data.name
        },

        // set the domain computed property to watch for any changes in 'domain' field
        domain() {
            return this.data.domain;
        },

        queryWord() {
            return this.search;
        }
    },

    watch: {
        // watch for any changes in the 'name' field and validate
        name() {
            this.check_name();
        },

        // watch for any changes in the 'domain' field and validate
        domain() {
            this.check_domain();
        },

        queryWord() {
            this.set_query(this.search);
        }
    },

    methods: {
        // store methods
        ...mapActions({ 
            add_client: 'add_client',
            set_query: 'set_query',
        }),

        // register new client
        register(bvModalEvt) {
            this.errors = {};

            bvModalEvt.preventDefault();

            // validate the form
            if (this.validate()) {

                // pass in the data to create new tenant
                axios.post('/api/tenant', this.data)
                .then( response => {
                    if (response.data == 200) {

                        // hide the modal
                        this.$bvModal.hide('newClient');

                        // add client to the clients list in store
                        this.add_client(this.data);

                        // show a successful dialog box
                        this.$swal({
                            title: 'Client Added Successfully!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        })

                        // empty the input fields
                        this.data = {
                            name: '',
                            domain: '',
                        };
                    }
                })
            }
        },

        // validate the registration form
        validate() {    
            // check the name and domain for any errors and return a boolean
            if (this.check_name() && this.domain_validity) return true;
            else return false;
        },

        // validate name
        check_name() {
            this.remove_validation_class('name');
            // check if the fields are empty
            if (!this.data.name.length) {
                if (this.$refs.name) this.$refs.name.classList.add('is-invalid');
                this.errors.name = 'Please enter an organization\'s name.';
                return false;
            } else {
                this.$refs.name.classList.add('is-valid');
                return true;
            }
        },

        // validate domain
        check_domain() {
            // remove the previous validation class
            this.remove_validation_class('domain');

            // remove the previous invalid feedback if on display
            let invalid = document.querySelector('.domain-exists').classList;
            if (!invalid.contains('hidden')) invalid.add('hidden');

            // set the errors of domain to empty to avoid double invalid-feedback showing
            this.errors.domain = '';

            // check if the domain already exists
            axios.get('/api/domain/check/' + this.data.domain) 
            .then( response => {
                if (response.data == 'already-exists') {
                    this.$refs.domain.classList.add('is-invalid');
                    document.querySelector('.domain-exists').classList.remove('hidden');
                    this.domain_validity = false;
                } else {
                    if (this.$refs.domain) this.$refs.domain.classList.add('is-valid');
                    this.domain_validity = true;
                }
            });

            // check if domain is empty
            if (!this.data.domain.length) {
                if (this.$refs.domain) this.$refs.domain.classList.add('is-invalid');
                this.errors.domain = 'Please enter a domain name for the organiztion.';    
                this.domain_validity = false;  
            }
        },

        // remove any validation classes from the elements
        remove_validation_class(element) {
            if(this.$refs[element].classList.contains('is-invalid')) this.$refs[element].classList.remove('is-invalid');
            if(this.$refs[element].classList.contains('is-valid')) this.$refs[element].classList.remove('is-valid');
        },

        // toggle the search bar in the dashboard
        search_toggle() {
            const searchBar = document.getElementById('search-div');
            if (searchBar.style.width == '100%') searchBar.style.width = '0';
            else searchBar.style.width = '100%';

            document.querySelector('.buttons').classList.toggle('hidden');
            document.querySelector('.dashboard').classList.toggle('hidden');
        },

        // get the query word and go to the search page
        query() {

            this.set_query(this.search);
        },
    }
}
</script>

<style scoped>
    .router-link-active {
        background-color: #0091D5;
    }  

    .router-link-active .menu h6 {
        color: #df6e44 !important;
    }

    .domain {
        position: relative;
    }

    .domain-suffix {
        position: absolute;
        top: 27px;
        right: 48px;
    }

    #domain:not(:placeholder-shown) + .domain-suffix {
        opacity: 1;
    }

    #domain:focus + .domain-suffix{
        opacity: 1;
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