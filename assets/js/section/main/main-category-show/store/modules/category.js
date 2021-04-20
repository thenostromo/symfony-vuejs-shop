const axios = require('axios');
import {StatusCodes} from "http-status-codes";

const state = () => ({
    categoryProducts: [],
    countOfProducts: 0,
    products: window.staticStore.products,
    staticStore: window.staticStore,
    page: window.staticStore.page,
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
    }
}

const actions = {
    async getProductsByCategory({ commit, getters, state }) {
        const url = getters.urlGetProductsByCategory
            + "?category=api/products/"
            + getters.selectedCategory.id
            + "&page=" + state.page
            + "&itemsPerPage=" + state.limit
            + "&isHidden=0&isDeleted=0";
        const result = await axios.get(url)
        if (result.data && result.status === StatusCodes.OK) {
            commit('setCountOfProductsByCategory', result.data.totalItems)
            commit('setCategoryProducts', result.data._embedded.item)
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
