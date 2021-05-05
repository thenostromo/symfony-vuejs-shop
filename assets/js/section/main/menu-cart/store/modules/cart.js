import { apiConfig } from "../../../../../utils/settings";
import { StatusCodes } from "http-status-codes";
import { concatUrlByParams } from "../../../../../utils/url-generator";

const axios = require("axios");

const state = () => ({
  cart: {},

  staticStore: {
    url: {
      apiCart: window.staticStore.urlCart,
      apiCartProduct: window.staticStore.urlCartProduct,
      assetImageProducts: window.staticStore.urlAssetImageProducts,
      viewCart: window.staticStore.urlCartIndex,
      viewProduct: window.staticStore.urlProductShow
    }
  }
});

const getters = {
  totalPrice(state) {
    let totalPrice = 0;
    if (!state.cart.cartProducts) {
      return 0;
    }
    state.cart.cartProducts.forEach(cartProduct => {
      totalPrice += cartProduct.product.price * cartProduct.quantity;
    });
    return totalPrice;
  },
  countCartProducts(state) {
    return state.cart.cartProducts ? state.cart.cartProducts.length : 0;
  }
};

const actions = {
  async getCart({ state, commit }) {
    const url = state.staticStore.url.apiCart;

    const result = await axios.get(url, apiConfig);
    if (
      result.data &&
      result.status === StatusCodes.OK &&
      result.data["hydra:member"].length
    ) {
      commit("setCart", result.data["hydra:member"][0]);
    }
  },
  async addCartProduct({ state, dispatch }, productDataRaw) {
    if (state.cart.id) {
      dispatch("addProductToExistCart", productDataRaw);
    } else {
      dispatch("createCartAndAddProduct", productDataRaw);
    }
  },
  async createCartAndAddProduct({ state, dispatch }, productDataRaw) {
    const url = state.staticStore.url.apiCart;
    const data = {
      cartProducts: [
        {
          product: "/api/products/" + productDataRaw.id,
          quantity: productDataRaw.quantity
        }
      ]
    };
    const result = await axios.post(url, data, apiConfig);

    if (result.data && result.status === StatusCodes.CREATED) {
     dispatch("getCart");
    }
  },
  async addProductToExistCart({ state, dispatch }, productDataRaw) {
    const url = state.staticStore.url.apiCartProduct;
    const data = {
      cart: "/api/carts/" + state.cart.id,
      product: "/api/products/" + productDataRaw.id,
      quantity: productDataRaw.quantity
    };
    const result = await axios.post(url, data, apiConfig);

    console.log(result);
    if (result.data && result.status === StatusCodes.CREATED) {
      dispatch("getCart");
    }
  },
  async removeCartProduct({ state, dispatch }, productId) {
    const url = concatUrlByParams(
      state.staticStore.url.apiCartProduct,
      productId
    );
    const result = await axios.delete(url, apiConfig);

    if (result.status === StatusCodes.NO_CONTENT) {
      dispatch("getCart");
    }
  },
  async cleanCart({ state, commit }) {
    const url = concatUrlByParams(
      state.staticStore.url.apiCart,
      state.cart.id
    );
    const result = await axios.delete(url, apiConfig);

    if (result.status === StatusCodes.NO_CONTENT) {
      commit("setCart", {});
    }
  }
};

const mutations = {
  setCart(state, cart) {
    state.cart = cart;
  },
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
