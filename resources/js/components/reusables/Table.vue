<template>
    <div>

        <div class="d-flex justify-content-between">
            <div>
                <h3 class="fw-bold text-uppercase me-4">{{ title }}</h3>
                <slot />
            </div>

            <div class="mb-2" v-if="rowsToShow">
                <label for="page-size">Rows to show:</label>
                <select class="from-control" @change="onPageSizeChange" id="page-size">
                    <option value="10">10</option>
                    <option value="15" selected>15</option>
                    <option value="15">30</option>
                    <option value="15">50</option>
                    <option value="15">100</option>
                </select>
            </div>
        </div>
        
        <!-- Gird Vue Table -->
        <ag-grid-vue
            :style="width_height" 
            class="ag-theme-alpine shadow shadow-sm"
            :columnDefs="columnDefs"
            :rowData="rowData"
            :rowSelection="rowSelection"
            :pagination="pagination"
            :paginationPageSize="paginationPageSize"
            angularCompilerHeaders="true"
            :rowClassRules="rowClassRules"
            @grid-ready="onGridReady"
            @cellValueChanged="onValueChanged"
            @selection-changed="onSelectionChanged"
        >
        </ag-grid-vue>

        <!-- Delete Button -->
        <button 
            class="btn btn-danger footer-btn mx-3" 
            :class="{ hidden: !clients.length }" 
            :disabled="!selectedIds.length" 
            v-b-modal.deleteClients
            v-if="deletable"
        >Delete</button>

        <!-- Payment Button -->
        <button
            class="btn btn-primary footer-btn"
            :class="{ hidden: !clients.length }"
            :disabled="!selectedIds.length"
            v-b-modal.renewClients
            v-if="renewable"
        >Renew</button>

        <!-- Modals -->

        <!-- Delete Modal -->
        <b-modal id="deleteClients" 
            :title="selectedNames.length == 1 ? 'Delete ' + selectedNames[0] + '?' : 'Delete these clients?' " 
            ok-title="Delete" 
            ok-variant="danger" 
            @ok="trash">
            <p>Are you sure you want to delete {{ selectedNames.length == 1 ? selectedNames[0] : 'these clients' }}?</p>
        </b-modal>

        <!-- Renew Modal -->
        <b-modal id="renewClients"
            :title="selectedNames.length == 1 ? 'Renew ' + selectedNames[0] + '?' : 'Renew these clients?'"
            ok-title="Renew"
            ok-variant="primary"
            @ok="renew">
            <p>Are you sure you want to renew {{ selectedNames.length == 1 ? selectedNames[0] : 'these clients' }}?</p>
        </b-modal>

    </div>
</template>

<script>
import { AgGridVue } from 'ag-grid-vue';

import { mapActions, mapGetters } from 'vuex';

export default {
    props: {
        title: [ String, 'required' ],
        columnDefs: Array,
        height: String,
        pagination: { type: Boolean, default: false },
        paginationPageSize: { type: Number, default: 5 },
        rowSelection: { type: String, default: 'multiple' },
        deletable: { type: Boolean, default: false },
        editable: { type: Boolean, default: false },
        renewable: { type: Boolean, default: false },
        API_URL: { type: String, default: '' },
        rowsToShow: { type: Boolean, default: false }
    },

    data() {
        return {
            gridApi: null,
            columnApi: null,
            rowData: null,
            width_height: 'width:100%; height:' + this.height,
            selectedIds: [],
            selectedNames: [],
            rowClassRules: null,
        }
    },

    computed: { 
        // store getters
        ...mapGetters({
            clients: 'get_clients',
            queryWord: 'query',
        }),

        // check client length
        clientsLength() {
            return this.clients.length;
        },
    },

    watch: {
        // fetch data when the client length changes
        clientsLength() {
            this.fetch();
        },

        // filter the data if there is any value in query
        queryWord() {
            this.gridApi.setQuickFilter(this.queryWord);
        }
    },

    components: { AgGridVue },

    mounted() {
        this.fetch();

        this.rowClassRules = {
            // if due date has been crossed
            'expired': (params) => {
                if (params.data.due) {
                    let today = Vue.moment().format('YYYY-MM-DD');
                    let due = Vue.moment(params.data.due).format('YYYY-MM-DD');
                    return Vue.moment(today).isAfter(due);                    
                }
            },

            'last-day-warning': (params) => {
                if (params.data.due) {
                    let today = Vue.moment().format('YYYY-MM-DD');
                    let due = Vue.moment(params.data.due).format('YYYY-MM-DD');
                    return Vue.moment(today).isSame(due);
                }
            }
        }
    },

    methods: {
        // store methods
        ...mapActions({
            set_clients: 'set_clients',
        }),

        // fetch rowData from the database and set it in the store and get the rowData in right format
        fetch() {
            fetch(this.API_URL + '/get')
            .then( res => res.json() )
            .then( res => {
                // stores the data in store, if it needs to store
                if (this.editable) this.set_clients(res);

                // sets the rowData in right format
                this.rowData = this.get_rowData(res);
            });
        },

        // push data in a right format for the table rowData
        get_rowData(api) {
            let remaining = '';
            let rowData = [];

            api.forEach((row) => {  
                if (this.title = 'Licenses') {
                    rowData.push({
                        name: row.name,
                        phone: row.phone,
                        username: row.username,
                        password: row.password
                    })
                } else {

                          
                    if (row.due_date) {
                        // set the today's date and due date of clients in same format
                        let today = Vue.moment().format('YYYY-MM-DD');
                        let dueDate = Vue.moment(row.due_date).format('YYYY-MM-DD');
                        
                        // set remaining to expired if the due date has passed expiration day
                        if (Vue.moment(today).isAfter(dueDate)) remaining = 'Expired';
                        else {

                            // set the remaining to the number of days left before expiration
                            let remainingd = (Vue.moment(dueDate).diff(today));
                            remaining = Vue.moment(remainingd).format('DD') - 1;
                        }
                    }

                    // push the data to rowData array for the use of the table
                    rowData.push(
                        {
                            name: row.name,
                            domain: row.id + '.' + process.env.MIX_APP_BASE_URL,
                            due: row.due_date,
                            remaining_days: remaining
                        }
                    );
                }
            });
            
            // set rowData to the required format we just created
            return rowData;
        },

        // delete or bulk delete from the table
        trash() {
            // if only one row is selected go to delete route
            if (this.selectedIds.length == 1) {
                axios.delete(this.API_URL + '/' + this.selectedIds[0])
                .then ( response => {
                    if (response.data == 200) {
                        this.fetch();
                        this.$swal({
                            title: 'Client Deleted Successfully!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else this.$swal({
                        title: 'Something went wrong!',
                        icon: 'error',
                    })
                });
            } else {
                // else go to bulk delete route

                axios.post(this.API_URL, { params: this.selectedIds, _method: 'DELETE' })
                .then ( response => {
                    if (response.data == 200) {
                        this.fetch();
                        this.$swal({
                            title: 'Clients Deleted Successfully!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        this.selectedIds = [];
                    } else this.$swal({
                        title: 'Something went wrong!',
                        icon: 'error',
                    })
                });
            }
        },

        // renew the clients
        renew() {
            // if only one client is selected, go to renew route
            if (this.selectedIds.length == 1) {
                axios.get(this.API_URL + '/renew/' + this.selectedIds[0])
                .then( response => {
                    if (response.data == 200) {
                        this.fetch();
                        this.$swal({
                            title: 'Client Renewed Successfully!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    } else this.$swal({
                        title: 'Something went wrong!',
                        icon: 'error',
                    })
                });
            } else {

                // else go to bulk renew route
                axios.post(this.API_URL + '/renew', { params: this.selectedIds })
                .then( response => {
                    if (response.data == 200) {
                        this.fetch();
                        this.$swal({
                            title: 'Clients Renewed Successfully!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        this.selectedIds = [];
                    } else this.$swal({
                        title: 'Something went wrong!',
                        icon: 'error',
                    })
                })
            }
        },

        // set the selected rows to selected array
        onSelectionChanged() {
            this.selectedIds = [];
            this.selectedNames = [];

            let selectedRows = this.gridApi.getSelectedNodes();
            
            for(let i = 0; i < selectedRows.length; i++) {
                this.selectedIds.push(this.clients[selectedRows[i].childIndex].id);
                this.selectedNames.push(this.clients[selectedRows[i].childIndex].name);
            }
        },

        // save the changes made in the database
        onValueChanged(event) {

            // make sure the value is not empty
            if (event.newValue != '') {
                // save the new value in data
                this.data = this.clients[event.rowIndex];
                this.data[event.column.colId] = event.newValue;

                // pass the data to be updated in database
                axios.patch(this.API_URL, this.data)
            } else this.fetch();
        },

        // change the paination page size as per the selection
        onPageSizeChange() {
            var value = document.getElementById('page-size').value;
            this.gridApi.paginationSetPageSize(Number(value));
        },

        // set the grid and column api and resize the table if needed
        onGridReady: function (params) {
            this.gridApi = params.api;
            this.columnApi = params.columnApi;
            params.api.sizeColumnsToFit();

            window.addEventListener('resize', function() {
                setTimeout(function() {
                    params.api.sizeColumnsToFit();
                })
            })
        },
    },
}
</script>

<style scoped>
    .footer-btn {
        position: relative;
        bottom: 42px;
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