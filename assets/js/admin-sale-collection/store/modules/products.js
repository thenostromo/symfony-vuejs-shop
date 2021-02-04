const axios = require('axios');

const state = () => ({
  selectedCategory: "",
  selectedProduct: "",
  newProductDiscountAmount: "",
  categoryProducts: [],

  saleCollectionProducts: [],
  saleCollectionProductIds: [],

  staticStore: window.staticStore
})

const getters = {
    freeCategoryProducts(state) {
        return state.categoryProducts.filter(
            item => (state.saleCollectionProductIds.indexOf(item.id) === -1)
        )
    },
    categories(state) {
        return state.staticStore.categories
    },
    saleCollectionId(state) {
        return state.staticStore.saleCollectionId
    },
    urlProductView(state) {
        return state.staticStore.urlProductView
    },
    urlGetProductsByCategory(state) {
        return state.staticStore.urlGetProductsByCategory
    },
    urlGetProductsBySaleCollection(state) {
        return state.staticStore.urlGetProductsBySaleCollection
    },
    urlAddProductToSaleCollection(state) {
        return state.staticStore.urlAddProductToSaleCollection
    },
    urlRemoveProductFromSaleCollection(state) {
        return state.staticStore.urlRemoveProductFromSaleCollection
    }
}

const actions = {
  async changedSelectedCategory({ commit, getters, state }) {
    let data = new FormData();
    data.append("categoryId", state.selectedCategory);

    const result = await axios.post(getters.urlGetProductsByCategory, data)

    if (result.data.success) {
      commit('setCategoryProducts', result.data.data)
      commit('setSaleCollectionProductIds')
    }
  },
  async getProductsBySaleCollection({ commit, getters }) {
    let data = new FormData();
    data.append("saleCollectionId", getters.saleCollectionId);
    const result = await axios.post(getters.urlGetProductsBySaleCollection, data)

    if (result.data.success) {
      commit('setSaleCollectionProducts', result.data.data)
      commit('setSaleCollectionProductIds')
    }
  },
  async addNewProduct({ getters, state }) {
    let data = new FormData();
    data.append("productId", state.selectedProduct);
    data.append("saleCollectionId", getters.saleCollectionId);
    data.append("discountAmount", state.newProductDiscountAmount);

    const result = await axios.post(getters.urlAddProductToSaleCollection, data)
  },
  async removeProduct({ getters, commit, state }, productId) {
    let data = new FormData();
    data.append("productId", productId);
    data.append("saleCollectionId", getters.saleCollectionId);

    const result = await axios.post(getters.urlRemoveProductFromSaleCollection, data)

    if (result.data.success) {
      commit('removeProductFromSaleCollection', productId)
    }
  }
}

const mutations = {
  setSelectedCategory (state, selectedCategory) {
    state.selectedCategory = selectedCategory
    console.log(state)
  },
  setSelectedProduct (state, selectedProduct) {
    state.selectedProduct = selectedProduct
  },
  setNewProductDiscountAmount (state, newProductDiscountAmount) {
    state.newProductDiscountAmount = newProductDiscountAmount
  },
  setSaleCollectionProducts (state, products) {
    state.saleCollectionProducts = products
  },
  setSaleCollectionProductIds (state) {
    state.saleCollectionProductIds = state.saleCollectionProducts.map((item) => item.id)
  },
  setCategoryProducts (state, products) {
    state.categoryProducts = products
  },
  removeProductFromSaleCollection (state, productId) {
    state.saleCollectionProducts = state.saleCollectionProducts.filter((item, index) => {
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
