import Vue from 'vue'
import Vuex from 'vuex'
import saleCollection from './modules/sale-collection'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
        saleCollection
    },
    strict: debug
})
