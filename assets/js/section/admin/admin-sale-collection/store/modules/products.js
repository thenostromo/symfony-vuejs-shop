import { StatusCodes } from "http-status-codes";
import axios from "axios";
import { apiConfig } from "../../utils/settings";
import {
  getUrlProductsByCategory,
  concatUrlByParams
} from "../../utils/url-generator";

const state = () => ({
  newProduct: {
    category: "",
    productId: "",
    pricePerOne: ""
  },

  categories: [],
  categoryProducts: [],
  saleCollectionProducts: [],
  saleCollectionProductIds: [],
  busyProductIds: [],

  staticStore: {
    saleCollectionId: window.staticStore.saleCollectionId,
    url: {
      apiCategories: window.staticStore.urlAPICategories,
      apiCategoryProducts: window.staticStore.urlAPICategoryProducts,
      apiSaleCollection: window.staticStore.urlAPISaleCollection,
      apiSaleCollectionProducts: window.staticStore.urlAPISaleCollectionProducts,
      viewProduct: window.staticStore.urlProductView
    }
  },
  viewProductsCountLimit: 25
})

const getters = {
  freeCategoryProducts(state) {console.log(state.categoryProducts)
    return state.categoryProducts.filter(
      item => state.busyProductIds.indexOf(item.id) === -1
    );
  }
}

const actions = {
  async getCategories({ commit, state }) {
    const url = state.staticStore.url.apiCategories;
    const result = await axios.get(url, apiConfig);

    if (result.data && result.status === StatusCodes.OK) {
      commit("setCategories", result.data["hydra:member"]);
    }
  },
  async getProductsByCategory({ commit, state }) {
    const url = getUrlProductsByCategory(
      state.staticStore.url.apiCategoryProducts,
      state.newProduct.category,
      state.viewProductsCountLimit
    );
    const result = await axios.get(url, apiConfig);

    if (result.data && result.status === StatusCodes.OK) {
      commit("setCategoryProducts", result.data["hydra:member"]);
    }
  },
  async getProductsBySaleCollection({ commit, state }) {
    const url = concatUrlByParams(
      state.staticStore.url.apiSaleCollection,
      state.staticStore.saleCollectionId
    );
    const result = await axios.get(url, apiConfig);

    if (result.data && result.status === StatusCodes.OK) {
      commit("setSaleCollectionProducts", result.data.saleCollectionProducts);
      commit("setBusyProductIds");
    }
  },
  async addNewProduct({ state, dispatch }) {
    const url = state.staticStore.url.apiSaleCollectionProducts;
    const data = {
      pricePerOne: state.newProduct.pricePerOne,
      product: "/api/products/" + state.newProduct.productId,
      saleCollection: "/api/sale_collections/" + state.staticStore.saleCollectionId
    };

    const result = await axios.post(url, data, apiConfig);
    if (result.data && result.status === StatusCodes.CREATED) {
      dispatch("getProductsBySaleCollection");
    }
  },
  async removeProduct({ state, dispatch }, productId) {
    const url = concatUrlByParams(
      state.staticStore.url.apiSaleCollectionProducts,
      productId
    );
    const result = await axios.delete(url, apiConfig);

    if (result.status === StatusCodes.NO_CONTENT) {
      dispatch("getProductsBySaleCollection");
    }
  }
}

const mutations = {
  setCategories(state, categories) {
    state.categories = categories;
  },
  setNewProductInfo(state, formData) {
    state.newProduct.category = formData.category;
    state.newProduct.productId = formData.productId;
    state.newProduct.pricePerOne = formData.pricePerOne;
  },
  setSaleCollectionProducts(state, saleCollectionProducts) {
    state.saleCollectionProducts = saleCollectionProducts;
  },
  setBusyProductIds(state) {
    state.busyProductIds = state.saleCollectionProducts.map(
      item => item.product.id
    );
  },
  setCategoryProducts(state, products) {
    state.categoryProducts = products;
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
