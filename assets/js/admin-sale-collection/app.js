import Vue from 'vue';
import Vuex from 'vuex'
import App from './App';
import store from './store'

import '../../css/app.css';

Vue.use(Vuex)

new Vue({
    el: '#app',
    store: store,
    render: h => h(App)
});
