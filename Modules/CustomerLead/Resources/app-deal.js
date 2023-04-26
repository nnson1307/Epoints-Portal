
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');
import Vue from 'vue'
import App from './vue-deal/App.vue'
import store from './vue-deal/store/index'
import router from "./vue-deal/router/index"
import PerfectScrollbar from "vue2-perfect-scrollbar";
import "vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css";
import Popover from 'vue-js-popover'

import moment from 'moment';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

//Select2
import '@/assets/css/select2.css';
import 'vue-multiselect/dist/vue-multiselect.min.css';
import '../../../public/static/backend/assets/demo/base/style.bundle.css';
import '../../../public/static/backend/css/customize.css';

Vue.use(PerfectScrollbar);
Vue.use(VueSweetalert2);
Vue.use(Popover, {tooltip: true});

window.Vue = require('vue');

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD/MM/YYYY')
    }
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/App.vue'));

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});
