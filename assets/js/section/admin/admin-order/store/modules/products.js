import {StatusCodes} from "http-status-codes";

const axios = require('axios');
const apiConfig = {
    headers: {
        "accept": "application/hal+json",
        "Content-Type": "application/json"
    }
}

const state = () => ({
  selectedCategory: "",
  selectedProduct: "",
  newProductQuantity: "",
  newProductPricePerOne: "",
  categoryProducts: [],

  orderProducts: [],
  orderProductIds: [],

  staticStore: window.staticStore,

  viewProductsCountLimit: 25
})

const getters = {
    freeCategoryProducts(state) {
        console.log(state.categoryProducts)
        return state.categoryProducts.filter(
            item => (state.orderProductIds.indexOf(item.id) === -1)
        )
    },
    categories(state) {
        return state.staticStore.categories
    },
    orderId(state) {
        return state.staticStore.orderId
    },
    urlProductView(state) {
        return state.staticStore.urlProductView
    },
    urlAPIOrder(state) {
        return state.staticStore.urlAPIOrder
    },
    urlAPICategoryProducts(state) {
        return state.staticStore.urlAPICategoryProducts
    },
    urlAPIOrderProducts(state) {
        return state.staticStore.urlAPIOrderProducts
    }
}

const actions = {
  async getProductsByCategory({ commit, getters, state }) {
      const url = getters.urlAPICategoryProducts
          + "?category=api/products/"
          + state.selectedCategory
          + "&page=1"
          + "&itemsPerPage=" + state.viewProductsCountLimit
          + "&isHidden=0";
      const result = await axios.get(url, apiConfig)

      if (result.data && result.status === StatusCodes.OK) {
          commit('setCategoryProducts', result.data._embedded.item)
          commit('setOrderProductIds')
      }
  },
  async getProductsByOrder({ commit, getters }) {
      const url = getters.urlAPIOrder + "/" + getters.orderId;
      const result = await axios.get(url, apiConfig)

      if (result.data && result.status === StatusCodes.OK) {
          commit('setOrderProducts', result.data._embedded.orderProducts)
          commit('setOrderProductIds')
      }
  },
  async addNewProduct({ getters, state }) {
      const url = getters.urlAPIOrderProducts
      const data = {
          pricePerOne: state.newProductPricePerOne,
          quantity: parseInt(state.newProductQuantity),
          product: "/api/products/" + state.selectedProduct,
          appOrder: "/api/orders/" + getters.orderId,
      }

      const result = await axios.post(url, data, apiConfig)
  },
  async removeProduct({ getters, commit, state }, productId) {
      const url = getters.urlAPIOrderProduct + "/" + productId;
      const result = await axios.delete(url, apiConfig)

      if (result.status === StatusCodes.NO_CONTENT) {
          commit('removeProductFromOrder', productId)
      }
  }
}

const mutations = {
  setSelectedCategory (state, selectedCategory) {
    state.selectedCategory = selectedCategory
  },
  setSelectedProduct (state, selectedProduct) {
    state.selectedProduct = selectedProduct
  },
  setNewProductPricePerOne (state, newProductPricePerOne) {
    state.newProductPricePerOne = newProductPricePerOne
  },
  setNewProductQuantity (state, newProductQuantity) {
    state.newProductQuantity = newProductQuantity
  },
  setOrderProducts (state, orderProducts) {
    const orderProductsFormatted = [];
      orderProducts.forEach((orderProduct) => {
          const originalProduct = orderProduct._embedded.product;
          const category = (originalProduct._embedded && originalProduct._embedded.category)
            ? originalProduct._embedded.category
            : null
          const viewTitle = '#' + originalProduct.id +
              ' ' + originalProduct.title +
              ' / P: ' + originalProduct.price + '$ ' +
              '/ Q: ' + originalProduct.quantity

          orderProductsFormatted.push({
            category: category,
            id: orderProduct.id,
            pricePerOne: orderProduct.pricePerOne,
            quantity: orderProduct.quantity,
            product: {
                id: originalProduct.id,
                title: viewTitle
            }
          })
      })
    state.orderProducts = orderProductsFormatted
  },
  setOrderProductIds (state) {
    state.orderProductIds = state.orderProducts.map((item) => item.id)
  },
  setCategoryProducts (state, products) {
    state.categoryProducts = products
  },
  removeProductFromOrder (state, productId) {
    state.orderProducts = state.orderProducts.filter((item, index) => {
        return item.id !== productId
    })
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
