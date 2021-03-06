import { StatusCodes } from "http-status-codes";
import axios from "axios";
import { apiConfig } from "../../../../../utils/settings";
import {
  getUrlProductsByCategory,
  concatUrlByParams
} from "../../../../../utils/url-generator";

const state = () => ({
  newOrderProduct: {
    category: "",
    productId: "",
    quantity: "",
    pricePerOne: ""
  },

  categories: [],
  categoryProducts: [],
  orderProducts: [],
  orderProductIds: [],
  busyProductIds: [],
  promoCode: {},

  staticStore: {
    orderId: window.staticStore.orderId,
    promoCodeId: window.staticStore.promoCodeId,
    url: {
      apiCategories: window.staticStore.urlAPICategories,
      apiCategoryProducts: window.staticStore.urlAPICategoryProducts,
      apiOrder: window.staticStore.urlAPIOrder,
      apiOrderProducts: window.staticStore.urlAPIOrderProducts,
      apiPromoCode: window.staticStore.urlAPIPromoCode,
      viewProduct: window.staticStore.urlProductView
    }
  },
  viewProductsCountLimit: 25
});

const getters = {
  freeCategoryProducts(state) {
    return state.categoryProducts.filter(
      item => state.busyProductIds.indexOf(item.id) === -1
    );
  }
};

const actions = {
  async getPromoCode({ commit, state }) {
    const url = concatUrlByParams(
      state.staticStore.url.apiPromoCode,
      state.staticStore.promoCodeId
    );
    const result = await axios.get(url, apiConfig);

    if (result.data && result.status === StatusCodes.OK) {
      commit("setPromoCode", result.data);
    }
  },
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
      state.newOrderProduct.category,
      state.viewProductsCountLimit
    );
    const result = await axios.get(url, apiConfig);

    if (result.data && result.status === StatusCodes.OK) {
      commit("setCategoryProducts", result.data["hydra:member"]);
    }
  },
  async getProductsByOrder({ commit, state }) {
    const url = concatUrlByParams(
      state.staticStore.url.apiOrder,
      state.staticStore.orderId
    );
    const result = await axios.get(url, apiConfig);

    if (result.data && result.status === StatusCodes.OK) {
      commit("setOrderProducts", result.data.orderProducts);
      commit("setBusyProductIds");
    }
  },
  async addNewProduct({ state, dispatch }) {
    const url = state.staticStore.url.apiOrderProducts;
    const data = {
      pricePerOne: state.newOrderProduct.pricePerOne,
      quantity: parseInt(state.newOrderProduct.quantity),
      product: "/api/products/" + state.newOrderProduct.productId,
      appOrder: "/api/orders/" + state.staticStore.orderId
    };

    const result = await axios.post(url, data, apiConfig);
    if (result.data && result.status === StatusCodes.CREATED) {
      dispatch("getProductsByOrder");
    }
  },
  async removeProduct({ state, dispatch }, productId) {
    const url = concatUrlByParams(
      state.staticStore.url.apiOrderProducts,
      productId
    );
    const result = await axios.delete(url, apiConfig);

    if (result.status === StatusCodes.NO_CONTENT) {
      dispatch("getProductsByOrder");
    }
  }
};

const mutations = {
  setPromoCode(state, promoCode) {
    state.promoCode = promoCode;
  },
  setCategories(state, categories) {
    state.categories = categories;
  },
  setNewProductInfo(state, formData) {
    state.newOrderProduct.category = formData.category;
    state.newOrderProduct.productId = formData.productId;
    state.newOrderProduct.quantity = formData.quantity;
    state.newOrderProduct.pricePerOne = formData.pricePerOne;
  },
  setOrderProducts(state, orderProducts) {
    state.orderProducts = orderProducts;
  },
  setBusyProductIds(state) {
    state.busyProductIds = state.orderProducts.map(item => item.product.id);
  },
  setCategoryProducts(state, products) {
    state.categoryProducts = products;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
