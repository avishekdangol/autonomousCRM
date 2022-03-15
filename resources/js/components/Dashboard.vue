<template>
    <div>
        <div class="row">
            <div class="col-6">
                <Table 
                    title="Recent Clients"
                    :columnDefs="recentColDefs"
                    API_URL="/api/tenant/recent"
                    height="32vh"
                    rowSelection=false
                />
            </div>

            <div class="col-6">
                <Table
                    title="Near Renewal"
                    :columnDefs="dueColDefs"
                    API_URL="/api/tenant/renew"
                    height="32vh"
                    rowSelection=false
                    :pagination="true"
                    :paginationPageSize=5
                />
            </div>
        </div>
        
    </div>
</template>

<script>
import Table from './reusables/Table.vue';

export default {
    data() {
        return {
            clients: [],

            recentColDefs: null,
            dueColDefs: null,
            rowData: null,
        }
    },

    components: { Table},

    mounted() {
        this.recentColDefs = [
            {
                headerName: 'Company Name',
                field: 'name'
            },

            {
                headerName: 'Domain',
                field: 'domain'
            }
        ];

        this.dueColDefs = [
            {
                headerName: 'Company Name',
                field: 'name'
            },

            {
                headerName: 'Due Date',
                field: 'due'
            },

            {
                headerName: 'Remaining Days',
                field: 'remaining_days'
            }
        ];
    },

    methods: {
        
    }
}
</script>