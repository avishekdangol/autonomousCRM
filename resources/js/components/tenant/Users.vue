<template>
    <div>
        <div class="d-flex align-items-baseline">
            <h3 class="heading">Users</h3>
            <a href="#" class="btn text-light mx-2" v-b-modal.createUser>ADD NEW</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th width="360px">Name</th>
                </tr>
            </thead>

            <tbody>
                <tr v-if="loading">
                    <td>
                        <div class="text-center my-4" >
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr v-if="!users.length && !loading">
                    <td width="500px">
                        There are no users yet. <span class="underlined-link" v-b-modal.createUser>Register now.</span>
                    </td>
                </tr>

                <tr class="items" v-for="user, index in users" :key="user.id">
                    <td width="560px">
                        <p class="lead">{{ user.name }}</p>
                        <span class="trash" @click="get_user(index)" v-b-modal.deleteUser>Remove</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- MODALS -->
        <b-modal id="deleteUser" :title="'Remove ' + user.name + '?'" centered ok-variant="danger" ok-title="Remove" @ok="destroy">
        </b-modal>

        <b-modal id="createUser" title="REGISTER NEW USER" centered ok-title="Register" @ok="create">
            <b-form class="needs-validation">
                <div class="form-floating my-3">
                    <input type="text" name="name" ref="name" class="form-control" id="name" placeholder="Name" v-model="data.name">
                    <label for="floatingInput">Name</label>   
                    <div class="invalid-feedback">{{ errors.name }}</div>
                </div>  

                <div class="form-floating my-3">
                    <input type="text" name="username" ref="username" class="form-control" id="username" placeholder="Username" v-model="data.username">
                    <label for="floatingInput">Username</label>   
                    <div class="invalid-feedback">{{ errors.username }}</div>         
                </div>

                <div class="form-floating my-3">
                    <input type="text" name="password" ref="password" class="form-control" id="password" placeholder="Password" v-model="data.password">
                    <label for="floatingInput">Password</label>   
                    <div class="invalid-feedback">{{ errors.password }}</div>         
                </div>

                <div class="form-floating my-3">
                    <input type="text" name="confirm-password" ref="confirm-password" class="form-control" id="confirm-password" placeholder="Confirm Password" v-model="data.password_confirmation">
                    <label for="floatingInput">Confirm Password</label>   
                    <div class="invalid-feedback">{{ errors.password_confirmation }}</div>         
                </div>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
import axios from "axios";
import { mapActions, mapGetters } from "vuex";

export default ({
    data() {
        return {
            users: {},
            user: {},
            data: {
                name: '',
                username: '',
                password: this.$tenant_id ?? 'autonomous',
                password_confirmation: this.$tenant_id ?? 'autonomous'
            },
            errors: {name: ''},
        }
    },

    computed: {
        ...mapGetters({
            loading: 'get_loading',
        })
    },

    async mounted() {
        this.set_loading(true);
        await this.fetch_data();
        this.set_loading(false);
    },

    methods: {
        ...mapActions(['set_loading']),

        async fetch_data($url = '/api/users') {
            await fetch($url)
            .then( res => res.json() )
            .then( res => {
                this.users = res.data;
            })
        },

        destroy() {  
            axios.get('/api/users/' + this.user.id)
            .then( response => {
                if( response.data == 'deleted') {
                    this.fetch_data();
                    this.$swal({
                        title: 'User has been removed successfully!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500,
                    })
                    this.$bvModal.hide('deleteUser');
                } else {
                    console.log(response.data);
                    this.$swal({
                        icon: 'error',
                        title: 'Something went wrong!',
                    })
                }
            })
        },

        create(bvModalEvt) {
            bvModalEvt.preventDefault();
            if (this.validate()) {
                axios.post('/register', this.data)
                .then( response => {
                    if (response.status == 200 || response.status == 201) {
                        this.$bvModal.hide('createUser');
                        this.fetch_data();
                        this.$swal({
                            title: 'User registered successfully!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    } else {
                        this.$swal({
                            title: 'Something went wrong!',
                            icon: 'error',
                        })
                    }
                })
            } 
        },

        validate() {
            this.errors = {};

            Object.values(this.$refs).forEach(item => {
                if (item.classList.contains('is-invalid')) item.classList.remove('is-invalid');
                else if (item.classList.contains('is-valid')) item.classList.remove('is-valid');
            })

            if (!this.data.name.length) {
                this.errors.name = "Please enter a name";
                this.$refs.name.classList.add('is-invalid');
            } else this.$refs.name.classList.add('is-valid');

            if (!this.data.username.length) {
                this.errors.username = "Please enter a username";
                this.$refs.username.classList.add('is-invalid');
                
            } else {
                Object.values(this.users).forEach(value => {
                    if (value.username === this.data.username) {
                        this.errors.username = "This username already exists. Please use another username";
                        this.$refs.username.classList.add('is-invalid');
                    } else this.$refs.username.classList.add('is-valid');
                })
            }

            if (!this.data.password.length) {
                this.data.password_confirmation = '';
                this.errors.password = "Please enter a password";
                this.$refs.password.classList.add('is-invalid');
            } else if (this.data.password != this.data.password_confirmation) {
                this.data.password = this.data.password_confirmation = '';
                this.errors.password = "Password doesn't match";
                this.$refs.password.classList.add('is-invalid');
            } else this.$refs.password.classList.add('is-valid');

            if (!Object.keys(this.errors).length) {
                return true;
            } else return false;
        },

        get_user(index) {
            this.user = this.users[index];
        },
    }
})
</script>
    
<style scoped>
    .btn {
        background-color: #333;
        font-size: 14px;
        transition: all 0.3s ease-in;
    }

    .btn:hover {
        background-color: #595959;
    }

    .items td {
        height: 72px;
    }

    .items .trash {
        display: none;
        color: #df4444;
        cursor: pointer;
    }

    .lead {
        margin-bottom: 0;
    }

    .items:hover .trash {
        display: block;
        text-decoration: underline;
    }

    .underlined-link {
        text-decoration: underline;
    }

    .underlined-link:hover {
        text-decoration: none;
    }

    /deep/ .modal-title {
        font-weight: bold;
    }

    /deep/ #userModal .modal-body {
        display:none;
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

    /deep/ .modal .btn-danger,
    /deep/ .modal .btn-primary {
        color: #f0f0f0;
    }

    /deep/ #floatingTextarea {
        height: 120px;
    }
</style>