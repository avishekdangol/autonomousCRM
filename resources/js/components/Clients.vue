<template>
    <div>
                            
        <Table
            title="Clients"
            height='77vh'
            :columnDefs="columnDefs"
            API_URL="/api/tenant"
            rowSelection="multiple"
            :pagination="true"
            :paginationPageSize="paginationPageSize"
            :deletable="true"
            :editable="true"
            :renewable="true"
            :rowsToShow="true">

            <div class="d-flex">
                <div class="last-day-box fw-bold">
                    <p class="mx-4">Last Day Warning</p>
                </div>

                <div class="expired-box fw-bold">
                    <p class="mx-4">Expired Warning</p>
                </div>
            </div>
        </Table>

        
    </div>
</template>

<script>
import Table from './reusables/Table.vue';

export default {
    data() {
        return {
            data: {
                id: '',
                name: '',
            },

            gridApi: null,
            columnApi: null,
            columnDefs: null,
            rowData: null,
            paginationPageSize: 15,
            selectedIds: [],
            selectedNames: [],
        };
    },

    components: { Table },

    mounted() {
        // column definitions for the table
        this.columnDefs = [
            { 
                headerName: 'Company Name',
                field: 'name',
                sortable: true, 
                checkboxSelection: true, 
                editable: true,
                unSortIcon: true,   
                headerCheckboxSelection: true,
            },

            { 
                headerName: 'Domain',
                field: 'domain',
                sortable: true, 
                unSortIcon: true,
            },

            {
                headerName: 'Due Date',
                field: 'due',
                sortable: true,
                unSortIcon: true,
            }
        ];
    },
   
    methods: {
        
    }
};

</script>

<style scoped>
    .last-day-box,
    .expired-box {
        position: relative;
    }

    .last-day-box p::before,
    .expired-box p::before {
        position: absolute;
        left: 0;
        top: 3px;
        content: '';
        width: 15px;
        height: 15px;
        border: 1px solid #999;
        border-radius: 2px;
    }

    .last-day-box p::before {
        background-color: #ffe6b3;
    }

    .expired-box p::before {
        background-color: #ffb3b3;
    }
</style>