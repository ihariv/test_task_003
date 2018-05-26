// assets/js/app.js
import Vue from 'vue';
import Resource from 'vue-resource';
import Calc from './components/Calc';
import BootstrapVue from 'bootstrap-vue'

Vue.use(BootstrapVue);

Vue.use(Resource);

/**
 * Create a fresh Vue Application instance
 */
new Vue({
    el: '#app',
    components: {Calc},
    data: {
        // declare message with an empty value
        name: ''
    },
});


var $ = require('jquery');
// JS is equivalent to the normal "bootstrap" package
// no need to set this to a variable, just require it
require('bootstrap-sass');

// or you can include specific pieces
// require('bootstrap-sass/javascripts/bootstrap/tooltip');
// require('bootstrap-sass/javascripts/bootstrap/popover');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});