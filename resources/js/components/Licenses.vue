<template>
    <div>
        <Table
            title="Licenses"
            height='77vh'
            :columnDefs="columnDefs"
            API_URL="/api/licenses"
            rowSelection="multiple"
            :pagination="true"
            :paginationPageSize="paginationPageSize"
            :deletable="true"
            :editable="true"
            :renewable="true"
            :rowsToShow="true"
        >
        </Table>
    </div>
</template>

<script>
import Table from './reusables/Table.vue';

export default {
    data() {
        return {
            columnDefs: null,
            paginationPageSize: 15,
        }
    },

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
                headerName: 'Phone Number',
                field: 'phone',
                sortable: true, 
                unSortIcon: true,
            },

            {
                headerName: 'Username',
                field: 'username',
                sortable: true,
                unSortIcon: true,
            },

            {
                headerName: 'Password',
                field: 'password',
                sortable: true,
                unSortIcon: true,
            }
        ];
    },

    components: { Table },
}
</script>