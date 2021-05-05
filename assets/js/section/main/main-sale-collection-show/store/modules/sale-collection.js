import { StatusCodes } from "http-status-codes";
import axios from "axios";
import { apiConfig } from "../../../../../utils/settings";
import {
  getUrlProductsBySaleCollection,
  concatUrlByParams
} from "../../../../../utils/url-generator";

const state = () => ({
  saleCollection: {},
  saleCollectionProducts: [],
  saleCollectionProductsTotalCount: 0,

  staticStore: {
    saleCollectionId: window.staticStore.saleCollectionId,
    url: {
      apiSaleCollection: window.staticStore.urlAPISaleCollection,
      apiSaleCollectionProducts: window.staticStore.urlAPISaleCollectionProducts,
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
      state.saleCollectionProductsTotalCount / state.viewProductsCountLimit
    );
  },
  showedProductsCount(state) {
    const calcCount = state.page + state.viewProductsCountLimit;
    return calcCount > state.saleCollectionProductsTotalCount
      ? state.saleCollectionProductsTotalCount
      : calcCount;
  }
};

const actions = {
  async getSaleCollection({ commit, state }) {
    const url = concatUrlByParams(
      state.staticStore.url.apiSaleCollection,
      state.staticStore.saleCollectionId
    );
    const result = await axios.get(url, apiConfig);

    if (result.data && result.status === StatusCodes.OK) {
      commit("setSaleCollection", result.data);
    }
  },
  async getProductsBySaleCollection({ commit, state }) {
    const url = getUrlProductsBySaleCollection(
      state.staticStore.url.apiSaleCollectionProducts,
      state.staticStore.saleCollectionId,
      state.page,
      state.viewProductsCountLimit
    );
    const result = await axios.get(url, apiConfig);

    if (result.data && result.status === StatusCodes.OK) {
      commit("setSaleCollectionProductsTotalCount", result.data["hydra:totalItems"]);
      commit("setSaleCollectionProducts", result.data["hydra:member"]);
    }

    commit("setIsLoading", false);
  }
};

const mutations = {
  setSaleCollection(state, saleCollection) {
    state.saleCollection = saleCollection;
  },
  setSaleCollectionProducts(state, products) {
    state.saleCollectionProducts = products;
  },
  setSaleCollectionProductsTotalCount(state, count) {
    state.saleCollectionProductsTotalCount = count;
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
