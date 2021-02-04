const axios = require('axios');

const state = () => ({
  selectedCategory: "",
  selectedProduct: "",
  newProductQuantity: "",
  newProductPricePerOne: "",
  categoryProducts: [],

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
    categories(state) {
        return state.staticStore.categories
    },
    orderId(state) {
        return state.staticStore.orderId
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
  async changedSelectedCategory({ commit, getters, state }) {
    let data = new FormData();
    data.append("categoryId", state.selectedCategory);

    const result = await axios.post(getters.urlGetProductsByCategory, data)

    if (result.data.success) {
      commit('setCategoryProducts', result.data.data)
      commit('setOrderProductIds')
    }
  },
  async getProductsByOrder({ commit, getters }) {
    let data = new FormData();
    data.append("orderId", getters.orderId);
    const result = await axios.post(getters.urlGetProductsByOrder, data)

    if (result.data.success) {
      commit('setOrderProducts', result.data.data)
      commit('setOrderProductIds')
    }
  },
  async addNewProduct({ getters, state }) {
    let data = new FormData();
    data.append("productId", state.selectedProduct);
    data.append("orderId", getters.orderId);
    data.append("pricePerOne", state.newProductPricePerOne);
    data.append("quantity", state.newProductQuantity)

    const result = await axios.post(getters.urlAddProductToOrder, data)
  },
  async removeProduct({ getters, commit, state }, productId) {
    let data = new FormData();
    data.append("productId", productId);
    data.append("orderId", getters.orderId);

    const result = await axios.post(getters.urlRemoveProductFromOrder, data)

    if (result.data.success) {
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
  setOrderProducts (state, products) {
    state.orderProducts = products
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
