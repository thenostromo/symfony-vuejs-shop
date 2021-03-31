const axios = require('axios');

const state = () => ({
    categoryProducts: [],
    countOfProducts: 0,
    products: window.staticStore.products,
    staticStore: window.staticStore,
    offset: window.staticStore.offset,
    limit: window.staticStore.limit,
    isLoading: true
})

const getters = {
    currentPage(state) {
        return Math.floor(state.offset / state.limit) + 1
    },
    currentCount(state) {
        const calcCount = state.offset + state.limit;
        return calcCount > state.countOfProducts
            ? state.countOfProducts
            : calcCount
    },
    selectedCategory(state) {
        return state.staticStore.selectedCategory
    },
    category(state) {
        return state.staticStore.selectedCategory
    },
    urlProductShow(state) {
        return state.staticStore.urlProductShow
    },
    getUrlAssetImageProducts(state) {
        return state.staticStore.urlAssetImageProducts
    },
    urlGetProductsByCategory(state) {
        return state.staticStore.urlGetProductsByCategory
    },
    urlGetCountOfProductsByCategory(state) {
        return state.staticStore.urlGetCountOfProductsByCategory
    }
}

const actions = {
    async getCountOfProductsByCategory({ commit, getters }) {
        let data = new FormData();
        data.append("categoryId", getters.selectedCategory.id);
        const result = await axios.post(getters.urlGetCountOfProductsByCategory, data)

        if (result.data.success) {
            commit('setCountOfProductsByCategory', result.data.data.count)
        }
    },
    async getProductsByCategory({ commit, getters, state }) {
        let data = new FormData();
        data.append("categoryId", getters.selectedCategory.id);
        data.append("offset", state.offset)
        data.append("limit", state.limit)
        const result = await axios.post(getters.urlGetProductsByCategory, data)

        if (result.data.success) {
            commit('setCategoryProducts', result.data.data)
        }

        commit('setIsLoading', false)
    },
}

const mutations = {
    setCategoryProducts (state, products) {
        state.categoryProducts = products
    },
    setCountOfProductsByCategory (state, count) {
      state.countOfProducts = count
    },
    setOffset (state, offset) {
      state.offset = offset
    },
    setIsLoading (state, isLoading) {
        state.isLoading = isLoading
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
