/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('bootstrap-vue');

window.Vue = require('vue').default;


import App from "../views/App.vue"
import router from "./router/router.js"
import store from "./store/store.js"
import vuetify from "../plugins/vuetify.js"

/*****************---------- FORM VALIDATION RULES --------*****************/
import { ValidationProvider, ValidationObserver } from "vee-validate"
require('./rules/FormRules')

Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)


/*************************------  Register Bootstrap   -----************** */
import { BootstrapVue } from "bootstrap-vue"
import "bootstrap-vue/dist/bootstrap-vue.css"
import "bootstrap-vue/dist/bootstrap-vue.css"

Vue.use(BootstrapVue)

/************** MOMENT *********************/
import VueMoment from "vue-moment"
import moment from 'moment-timezone'

Vue.use(VueMoment, { moment })

/**************************   Load Components   ****************************/
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


/******** Main ***********/
const app = new Vue({
	el: '#app',
	components: { App },
	vuetify,
	router,
	store
});


