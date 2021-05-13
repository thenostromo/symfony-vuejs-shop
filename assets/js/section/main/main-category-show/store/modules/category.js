import { StatusCodes } from "http-status-codes";
import axios from "axios";
import { apiConfig } from "../../../../../utils/settings";
import {
  getUrlProductsByCategory,
  concatUrlByParams
} from "../../../../../utils/url-generator";

const state = () => ({
  category: {},
  categoryProducts: [],
  categoryProductsTotalCount: 0,

  staticStore: {
    categoryId: window.staticStore.categoryId,
    url: {
      apiCategory: window.staticStore.urlAPICategory,
      apiCategoryProducts: window.staticStore.urlAPICategoryProducts,
      assetImageProducts: window.staticStore.urlAssetImageProducts,
      viewProduct: window.staticStore.urlProductShow
    }
  },

  page: 1,
  viewProductsCountLimit: 4,
  isLoading: true
});

const getters = {
  pagesCount(state) {
    return Math.round(
      state.categoryProductsTotalCount / state.viewProductsCountLimit
    );
  },
  showedProductsCount(state) {
    const calcCount = state.page + state.viewProductsCountLimit;
    return calcCount > state.categoryProductsTotalCount
      ? state.categoryProductsTotalCount
      : calcCount;
  }
};

const actions = {
  async getCategory({ commit, state }) {
    const url = concatUrlByParams(
      state.staticStore.url.apiCategory,
      state.staticStore.categoryId
    );
    const result = await axios.get(url, apiConfig);

    if (result.data && result.status === StatusCodes.OK) {
      commit("setCategory", result.data);
    }
  },
  async getProductsByCategory({ commit, state }) {
    const url = getUrlProductsByCategory(
      state.staticStore.url.apiCategoryProducts,
      state.staticStore.categoryId,
      state.page,
      state.viewProductsCountLimit
    );
    const result = await axios.get(url, apiConfig);

    if (result.data && result.status === StatusCodes.OK) {
      commit("setCategoryProductsTotalCount", result.data["hydra:totalItems"]);
      commit("setCategoryProducts", result.data["hydra:member"]);
    }

    commit("setIsLoading", false);
  }
};

const mutations = {
  setCategory(state, category) {
    state.category = category;
  },
  setCategoryProducts(state, products) {
    state.categoryProducts = products;
  },
  setCategoryProductsTotalCount(state, count) {
    state.categoryProductsTotalCount = count;
  },
  setPage(state, page) {
    state.page = page;
  },
  setIsLoading(state, isLoading) {
    state.isLoading = isLoading;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
