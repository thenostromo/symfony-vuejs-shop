const axios = require('axios');

const state = () => ({
    cartProducts: [],
    alertInfo: null,
    promoCodeInfo: {
        value: '',
        discount: 0
    },
    isSentForm: false,
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
    urlApprovePromoCode(state) {
        return state.staticStore.urlApprovePromoCode
    },
    urlMakeCartOrder(state) {
        return state.staticStore.urlMakeCartOrder
    },
    getUrlAssetImageProducts(state) {
        return state.staticStore.urlAssetImageProducts
    },
    totalPrice(state, getters) {
        let totalPrice = getters.priceWithoutPromoCode
        return totalPrice - ((totalPrice / 100)) * state.promoCodeInfo.discount
    },
    priceWithoutPromoCode(state) {
        let totalPrice = 0
        state.cartProducts.forEach((item) => {
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
        let cartJSON = localStorage.getItem('cart');
        const cart = cartJSON ? JSON.parse(cartJSON) : [];
        commit('setProductsOfCart', cart)
    },
    async approvePromoCode({commit, getters, state}) {
        commit('cleanAlertInfo')

        let data = new FormData();
        data.append("promo_code", state.promoCodeInfo.value);
        const result = await axios.post(getters.urlApprovePromoCode, data)

        if (result.data.success) {
            commit('setPromoCodeInfo', {discount: result.data.data.discount})
            commit('setAlertInfo', {
                type: 'success',
                message: 'Your promo code has approved!'
            })
        } else {
            commit('setAlertInfo', {
                type: 'warning',
                message: 'Entered promo code not found.'
            })
        }
    },
    async makeOrder({commit, getters, state}) {
        let data = new FormData();
        console.log(state)
        data.append("cart", JSON.stringify(state.cartProducts));
        data.append("promoCodeValue", state.promoCodeInfo.value);
        const result = await axios.post(getters.urlMakeCartOrder, data)

      //  if (result.data.success) {
            commit('setAlertInfo', {
                type: 'success',
                message: 'Thank you for your purchase! Our manager will contact with you in 24 hours.'
            })
            state.isSentForm = true
      //  }
    }
}

const mutations = {
    setProductsOfCart (state, products) {
        state.cartProducts = products
    },
    setPromoCodeInfo (state, model) {
        for (let key in model) {
            state.promoCodeInfo[key] = model[key]
        }
        console.log(model, state)
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
    },
    cleanAlertInfo (state) {
        state.alertInfo = null
    },
    setAlertInfo (state, model) {
        state.alertInfo = {
            type: model.type,
            message: model.message
        }
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
