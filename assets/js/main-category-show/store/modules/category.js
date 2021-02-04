const axios = require('axios');

const state = () => ({
    selectedCategory: "",

    orderProducts: [],
    orderProductIds: [],

    staticStore: window.staticStore
})

const getters = {
    freeCategoryProducts(state) {
        return state.categoryProducts.filter(
            item => (state.orderProductIds.indexOf(item.id) === -1)
        )
    },
    category(state) {
        console.log(state.staticStore)
        return state.staticStore.category
    },
    urlProductView(state) {
        return state.staticStore.urlProductView
    },
    urlGetProductsByCategory(state) {
        return state.staticStore.urlGetProductsByCategory
    },
    urlGetProductsByOrder(state) {
        return state.staticStore.urlGetProductsByOrder
    },
    urlAddProductToOrder(state) {
        return state.staticStore.urlAddProductToOrder
    },
    urlRemoveProductFromOrder(state) {
        return state.staticStore.urlRemoveProductFromOrder
    }
}

const actions = {
    async getProductsByOrder({ commit, getters }) {
        let data = new FormData();
        data.append("orderId", getters.orderId);
        const result = await axios.post(getters.urlGetProductsByOrder, data)

        if (result.data.success) {
            commit('setOrderProducts', result.data.data)
            commit('setOrderProductIds')
        }
    }
}

const mutations = {
    setCategoryProducts (state, products) {
        state.categoryProducts = products
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
