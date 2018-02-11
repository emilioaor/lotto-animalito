
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('register-user-form', require('./components/index/registerUserForm.vue'));
Vue.component('login-user-form', require('./components/index/loginUserForm.vue'));
Vue.component('register-ticket', require('./components/user/registerTicket.vue'));
Vue.component('register-transfer', require('./components/user/registerTransfer.vue'));
Vue.component('register-withdraw', require('./components/user/registerWithdraw.vue'));
Vue.component('bank-data-update', require('./components/user/bankDataUpdate.vue'));
Vue.component('change-password', require('./components/user/changePassword.vue'));
Vue.component('animal-gain', require('./components/user/animalGain.vue'));
Vue.component('process-transfer', require('./components/user/processTransfer.vue'));

const app = new Vue({
    el: '#app'
});
