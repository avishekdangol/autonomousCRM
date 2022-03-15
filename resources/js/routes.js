import PageNotFound from './components/404.vue';
import Dashboard from './components/Dashboard.vue';
import Clients from './components/Clients.vue';
import Licenses from './components/Licenses.vue';

import TenantUsers from './components/tenant/Users.vue';
import TenantLeadsContent from './components/tenant/LeadsContent.vue';
import TenantDashboard from './components/tenant/Dashboard.vue';

const routes = [
    {
        path: '/',
        component: TenantDashboard,
        name: 'TenantDashboard',
        beforeEnter: (to, from, next) => {
            if (document.querySelector('meta[name="tenant_id"]')) next();
            else next({ name: 'dashboard' })
        }
    },

    // Central Domain Routes 

    {
        path: '/',
        component: Dashboard,
        name: 'dashboard',
    },

    {
        path: '/clients',
        component: Clients,
        name: 'clients',
    },

    {
        path: '/licenses',
        component: Licenses,
        name: 'licenses',
    },

    // Tenant Routes
    {
        path: '/l',
        component: TenantLeadsContent,
    },

    {
        path: '/l/followup',
        component: TenantLeadsContent,
    },

    {
        path: '/l/demo',
        component: TenantLeadsContent,
    },

    {
        path: '/l/training',
        component: TenantLeadsContent
    },
   
    {
        path: '/a',
        component: TenantLeadsContent,
    },

    {
        path: '/a/followup',
        component: TenantLeadsContent
    },

    {
        path: '/a/demo',
        component: TenantLeadsContent
    },

    {
        path: '/a/all',
        component: TenantLeadsContent
    },

    {
        path: '/a/training',
        component: TenantLeadsContent
    },

    {
        path: '/sales',
        component: TenantLeadsContent,
        name: 'Sales'
    },

    {
        path: '/users',
        component: TenantUsers,
        name: 'Users',
        beforeEnter: (to, from, next) => {
            if (document.querySelector('meta[name="admin"]').content == 1) next();
            else next({ name: '404' })
        }
    },

    // Query 
    {
        path: "/query",
        component: TenantLeadsContent,
    },

    // Page not found for any other routes 
    {
        path: "*", 
        component: PageNotFound,
        name: '404'
    }
]

export default routes