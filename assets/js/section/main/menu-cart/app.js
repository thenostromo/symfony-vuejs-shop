import Vue from 'vue';
import Vuex from 'vuex'
import App from './App';
import store from './store'

Vue.use(Vuex)

const vueMenuCartInstance = new Vue({
    el: '#appMenuCart',
    store: store,
    render: h => h(App)
});

window.vueMenuCartInstance = {}
window.vueMenuCartInstance.getProductsOfCart =
    () => vueMenuCartInstance.$store.dispatch('cart/getProductsOfCart')
window.vueMenuCartInstance.addCartProduct =
  (productData) => vueMenuCartInstance.$store.dispatch('cart/addCartProduct', productData)
