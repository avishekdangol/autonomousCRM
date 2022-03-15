/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

 require('./bootstrap');
 import { BootstrapVue } from 'bootstrap-vue';
  
 import VueRouter from 'vue-router';
 import routes from './routes';
 
 import Vue from 'vue';
 import vuex from 'vuex';
 
 import store from './store/index.js';
 import VueSweetalert2 from 'vue-sweetalert2';
 
 window.Vue = require('vue').default;
 
 /**
  * The following block of code may be used to automatically register your
  * Vue components. It will recursively scan this directory for the Vue
  * components and automatically register them with their "basename".
  *
  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
  */
 
 // const files = require.context('./', true, /\.vue$/i)
 // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
 
 Vue.component('tenant-layout', require('./components/tenant/Layout.vue').default);
 Vue.component('layout', require('./components/Layout.vue').default);
 Vue.component('change-password', require('./components/ChangePassword.vue').default);
 Vue.use(BootstrapVue);
 Vue.use(VueRouter);
 Vue.use(vuex);
 Vue.use(VueSweetalert2);
 Vue.use(require('vue-moment'));
 
 const router = new VueRouter({
     mode: 'history',
     base: process.env.APP_URL,
     routes,
 })
 
 Vue.prototype.$admin = document.querySelector("meta[name='admin']").getAttribute('content');
 Vue.prototype.$user_id = document.querySelector("meta[name='user_id']").getAttribute('content');
 Vue.prototype.$token = document.querySelector("meta[name='csrf-token']").getAttribute('content');

 if (document.querySelector("meta[name='tenant_id']")) {
    Vue.prototype.$tenant_id = document.querySelector("meta[name='tenant_id']").getAttribute('content');
 }
 
 /**
  * Next, we will create a fresh Vue application instance and attach it to
  * the page. Then, you may begin adding components to this application
  * or customize the JavaScript scaffolding to fit your unique needs.
  */
 
 const app = new Vue({
     el: '#app',
     router,
     store
 });

 