<template>
    <div>
        <div class="row mx-2 justify-content-between">
            <div class="col-md-6 col-sm-12">

                <!-- Leads Chart by Status -->
                <div class="charts border shadow-sm">
                    <h5 class="heading mb-4">Leads</h5>
                    <canvas id="chart" height="180"></canvas>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">

                <!-- Leads Chart by Time Period -->
                <div class="charts border shadow-sm">
                    <div class="d-flex justify-content-between">
                        <h5 class="heading">Leads by Time Period</h5>
                        
                        <div class="font-weight-bold">
                            <label for="status">Status:</label>
                            <select name="status" id="status" v-model="fetch_status">
                                <option value="first_call" selected>First Call</option>
                                <option value="followup">Followup</option>
                                <option value="demo">Demo</option>
                                <option value="training">Training</option>
                                <option value="sales">Sales</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="border border-2 border-rounded">
                            <button class="btn" :class="{active: time_period == 'd'}" @click="set_period('d')">Daily</button>    
                            <button class="btn" :class="{active: time_period == 'w'}" @click="set_period('w')">Weekly</button>    
                            <button class="btn" :class="{active: time_period == 'm'}" @click="set_period('m')">Monthly</button>  
                        </div> 
                    </div>

                    <div class="spinner text-center my-4" v-if="loading == true">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <div class="canvas-wrap"><canvas id="chartTime" height="150"></canvas></div>
                    <div class="" v-if="loading == false">
                        <p class="text-center fw-bold" v-if="time_period == 'm'">For the year of {{ new Date().getFullYear() }}</p>
                        <p class="text-center small mt-2" v-if="counts.all.leads == 0">There are no data to show</p>
                    </div>
                </div>               
            </div>
        </div>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';
import { mapActions, mapGetters } from 'vuex';

export default ({
    data() {
        return {
            counts: {
                all: {},
                m: {},
                w: {},
                d: {},
            },
            fetch_status: 'first_call',
            time_period: 'd',
            LeadsChart: '',
            LeadsTimeChart: '',

            tenantID: this.$tenant_id,
        }
    },

    computed: {
        ...mapGetters({
            loading: 'get_loading',
        }),

        check_fetch_status() {
            return this.fetch_status;
        },

        check_period() {
            return this.time_period;
        }
    },

    watch: {
        async check_fetch_status(newValue) {
            this.set_loading(true);
            this.LeadsTimeChart.destroy();
            await this.fetchByTime(this.time_period, newValue);

            setTimeout(() => {
                this.createChart('chartTime', this.get_periodic_data());
                this.set_loading(false);
            }, 150);
        },

        async check_period(newValue) {
            this.set_loading(true);
            this.LeadsTimeChart.destroy();
            await this.fetchByTime(newValue, this.fetch_status);

            setTimeout(() => {
                this.createChart('chartTime', this.get_periodic_data());
                this.set_loading(false);
            }, 150);
        }
    },

    async mounted() {
        await this.fetch();
        await this.fetchByTime(this.time_period, this.fetch_status);

        this.createChart('chart', this.get_data());
        this.createChart('chartTime', this.get_periodic_data());

        // initialize a constant with channel name
        const private_channel = "tenant.leads." + this.tenantID;

        // listen to any events
        Echo.private(private_channel)
        .listen('Leads', async () => {
            this.set_loading(true);
            this.LeadsChart.destroy();
            this.LeadsTimeChart.destroy();

            await this.fetch();
            await this.fetchByTime(this.time_period, this.fetch_status);

            setTimeout( () => {
                this.createChart('chart', this.get_data());
                this.createChart('chartTime', this.get_periodic_data());
                this.set_loading(false);
            }, 1)
        })     
    },  

    methods: {
        ...mapActions(['set_loading']),

        async fetch() {
            await fetch('/api/count')
            .then( res => res.json() )
            .then( res => {
                this.counts.all = res;
            })
        },

        async fetchByTime(period, status) {
            await fetch('/api/count/' + period + '/' + status)
            .then( res => res.json() )
            .then( res => {
                this.counts[period] = res;
            })
        },

        get_data() {
            return {
                type: 'bar',
                data: {
                    labels: this.counts.all.leads == 0 ? ['There are no data to show'] : ['Leads'],
                    datasets: [
                        {
                            label: 'First Call',
                            data: [this.counts.all.firstcall],
                            backgroundColor: '#1C4E80',
                            borderWidth: '1'
                        },

                        {
                            label: 'Followup',
                            data: [this.counts.all.followup],
                            backgroundColor: '#A5D8DD',
                            borderWidth: '1'
                        },

                        {
                            label: 'Demo',
                            data: [this.counts.all.demo],
                            backgroundColor: '#EA6A47',
                            borderWidth: '1'
                        },

                        {
                            label: 'Training',
                            data: [this.counts.all.training],
                            backgroundColor: '#0091D5',
                            borderWidth: '1'
                        },

                        {
                            label: 'Sales',
                            data: [this.counts.all.sales],
                            backgroundColor: '#DBAE58',
                            borderWidth: '1'
                        },

                        {
                            label: 'Total Leads',
                            data: [this.counts.all.leads],
                            backgroundColor: '#488A99',
                            borderWidth: '1'
                        }
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    }
                }
            }
        },

        get_periodic_data() {
            // DECLARING VARIABLES
            let label;
            let labels;
            let data = [];
            var week_num = [];

            let date = new Date();

            // GETTING CURRENT WEEK NUMBER
            var oneJan = new Date(date.getFullYear(),0,1);
            var numberOfDays = Math.floor((date - oneJan) / (24 * 60 * 60 * 1000));
            var result = Math.ceil(( date.getDay() + 1 + numberOfDays) / 7);
            if (result == 53) result = 52;
            
            // DECLARING CONSTANTS
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const weeks = [];
            for (let i = 0; i < 4; i++) {
                // we check if the result (current week number calculated) is 0 or not
                // if it is 0, we add 52, since the database gives us a week number of 53
                // else we continue
                let num = result - i < 1 ? result + 52 - i : result - i;

                // if the current week is below 10, we add a 0 in front, so week number 1 will be 01
                if (num < 10) num = '0' + num;
                week_num.unshift(num);
            }

            for (let i = 0; i < week_num.length; i++) {
                weeks.push(this.getDateOfWeek(week_num[i], date.getFullYear()));
            }
            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

            // ALLOCATING VALUES TO THE VARIABLES
            if (this.time_period == 'd') {
                labels = days;
                for (let i = 0; i < days.length; i++) {
                    data.push(this.counts.d[days[i]] ? this.counts.d[days[i]].length : 0);
                }
            } else if (this.time_period == 'w') {
                labels = weeks;
                for (let i = 0; i < week_num.length; i++) {
                    data.push(this.counts.w[week_num[i]] ? this.counts.w[week_num[i]].length : 0);
                }
            } else if (this.time_period == 'm') {
                labels = months;
                for (let i = 0; i < months.length; i++) {
                    data.push(this.counts.m[months[i]] ? this.counts.m[months[i]].length : 0);
                }
            }

            label = this.get_label(this.fetch_status);

            // RETURNING CHART DATA
            return {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: label,
                            data: data,
                            backgroundColor: '#0091D5',
                        },
                    ],
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                        }
                    }
                },
            }
        },

        createChart(id, data) {
            const ctx = document.getElementById(id);

            if (id == 'chart') this.LeadsChart = new Chart(ctx, data);
            else if (id == 'chartTime') this.LeadsTimeChart = new Chart(ctx, data);
        },

        getDateOfWeek(w, y) {
            var simple = new Date(y, 0, 1 + (w - 1) * 7);
            var dow = simple.getDay();
            var ISOweekStart = simple;
            if (dow <= 4) ISOweekStart.setDate(simple.getDate() - simple.getDay() + 1);
            else ISOweekStart.setDate(simple.getDate() + 8 - simple.getDay());
            let ISOweekStartSplit = ISOweekStart.toDateString().split(' ');
            ISOweekStart = ISOweekStartSplit[1] + ' ' + ISOweekStartSplit[2];
            return ISOweekStart;
        },

        get_label(label) {
            label = label.split('_');
            label = label.map((word) => {
                return word.charAt(0).toUpperCase() + word.slice(1);
            });

            return label.join(' ');
        },

        set_period(period) {
            this.time_period = period;
        }
    }
})
</script>

<style scoped>
    .charts {
        position: relative;
        min-height: 480px;
        background-color: #fff;
        padding: 24px;
    }

    .spinner {
        position: absolute;
        top: 40%;
        left: 50%;
    }

    .canvas-wrap {
        min-height: 320px;
    }

    .loading-icon {
        width: 60px;
    }

    .btn {
        outline: none;
        transition: all 0.3s ease-in;
    }

    .btn:hover {
        background-color: #1c4e80;
        color: #f1f1f1;
    }

    .btn:focus {
        box-shadow: none;
    }

    .active {
        background-color: #1c4e80;
        color: #f1f1f1;
    }
</style>

