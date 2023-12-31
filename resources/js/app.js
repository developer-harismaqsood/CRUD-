/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue';

import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

Vue.use(VueToast);

import VeeValidate from 'vee-validate';
import { Validator } from 'vee-validate';

// alert popup
import VueSimpleAlert from "vue-simple-alert";
Vue.use(VueSimpleAlert);

import "skeleton-screen-css";

// email is name="email" and second email is validation value
const dict = {
  custom: {
    email: {
      email: 'The email address must include @'
    },
    phone: {
      numeric: 'The field must inlcude only number'
    }
  }
};


Validator.localize('en', dict);


Vue.use(VeeValidate, {
    events: 'input|change|blur',
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('dashboard', require('./components/Dashboard.vue').default);
Vue.component('user-management', require('./components/UserManagement.vue').default);
Vue.component('company-management', require('./components/CompanyManagement.vue').default);
Vue.component('employee-management', require('./components/EmployeeManagement.vue').default);
Vue.component('password-setting', require('./components/PasswordSetting.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
