<template>
    <div>
        <span v-b-modal.pwModal>Change Password</span>

        <!-- MODAL -->
        <b-modal id="pwModal" title="Change Password" ok-title="Submit" @ok="submit" centered>
            <b-form class="needs-validation">
                <div class="form-floating mb-3">
                    <input type="password" ref="current" id="current" class="form-control" placeholder="Current Password" v-model="data.current">
                    <label for="current">Current Password</label>
                    <div class="invalid-feedback">{{ errors.current }}</div>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" ref="password" id="password" class="form-control" placeholder="New Password" v-model="data.password">
                    <label for="password">New Password</label>
                    <div class="invalid-feedback">{{ errors.password }}</div>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" ref="password_confirmation" id="confirm-password" class="form-control" placeholder="Confirm Password" v-model="data.password_confirmation">
                    <label for="confirm-password">Confirm Password</label>
                </div>
            </b-form>

        </b-modal>
    </div>
</template>

<script>
export default ({

    data() {
        return {
            data: {
                id: this.$user_id,
                current: '',
                password: '',
                password_confirmation: '',
            },
            errors: {}
        }
    },

    methods: {
        submit(bvModalEvt) {
            bvModalEvt.preventDefault();

            if (this.validate()) {
                axios.patch('/api/users', this.data )
                .then( response => {
                    
                    if (response.data == 'success') {
                        this.$bvModal.hide('pwModal');
                        this.data.current = this.data.password = this.data.password_confirmation = '';
                        this.$swal({
                            title: 'Your Password has been Changed Successfully!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    } else if (response.data == 'failed') {
                        this.errors = {};
                        this.$refs.current.classList.add('is-invalid');
                        this.errors.current = "Wrong Password!";
                    }
                })
            }
            
        },

        validate() {
            this.errors = {};
            Object.values(this.$refs).forEach(value => {
                if (value.classList.contains('is-invalid')) value.classList.remove('is-invalid');
                else if (value.classList.contains('is-valid')) value.classList.remove('is-valid');
            })

            if (!this.data.current.length) {
                this.$refs.current.classList.add('is-invalid');
                this.errors.current = "Please enter your current password";
            } 

            if (!this.data.password.length) {
                this.data.password_confirmation = '';
                this.$refs.password.classList.add('is-invalid');
                this.errors.password = "Please enter your new password";
            } else if (this.data.current == this.data.password) {
                this.data.password = this.data.password_confirmation = '';
                this.$refs.password.classList.add('is-invalid');
                this.errors.password = "Your new password can't be the same as your current password";
            } else if (!this.data.password_confirmation.length || this.data.password != this.data.password_confirmation) {
                this.data.password = this.data.password_confirmation = '';
                this.$refs.password.classList.add('is-invalid');
                this.errors.password = "Passwords doesn't match";
            } 

            if (!Object.keys(this.errors).length) {
                return true;
            } else return false;

        }
    }
})
</script>


<style scoped>
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