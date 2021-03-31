const axios = require('axios');

const state = () => ({
    cartProducts: [],
    staticStore: window.staticStore
})

const getters = {
    cartProducts(state) {
        return state.staticStore.cartProducts
    },
    urlCartIndex(state) {
        return state.staticStore.urlCartIndex
    },
    urlProductShow(state) {
        return state.staticStore.urlProductShow
    },
    urlMakeCartOrder(state) {
        return state.staticStore.urlMakeCartOrder
    },
    getUrlAssetImageProducts(state) {
        return state.staticStore.urlAssetImageProducts
    },
    totalPrice(state) {
        let totalPrice = 0
        state.cartProducts.forEach((item) => {
            console.log(item)
            totalPrice += item.price * item.quantity
        })
        return totalPrice
    },
    countCartProducts(state) {
        return state.cartProducts.length
    }
}

const actions = {
    getProductsOfCart({commit}) {
        let cartJSON = sessionStorage.getItem('cart');
        const cart = cartJSON ? JSON.parse(cartJSON) : [];
        commit('setProductsOfCart', cart)
    },
    async makeOrder({commit, state}) {
        let data = new FormData();
        data.append("cart", JSON.stringify(state.cartProducts));
        const result = await axios.post(getters.urlMakeCartOrder, data)

        if (result.data.success) {
            //commit('setCountOfProductsByCategory', result.data.data.count)
        }
    }
}

const mutations = {
    setProductsOfCart (state, products) {
        state.cartProducts = products
    },
    removeProductFromCart (state, productId) {
        state.cartProducts = state.cartProducts.filter((item) => {
            return item.id !== productId
        })
        sessionStorage.setItem('cart', JSON.stringify(state.cartProducts));
    },
    cleanCart (state) {
        state.cartProducts = []
        sessionStorage.setItem('cart', JSON.stringify([]))
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
