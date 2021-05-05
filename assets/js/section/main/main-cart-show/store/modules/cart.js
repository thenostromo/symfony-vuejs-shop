import { apiConfig } from "../../../../../utils/settings";
import { StatusCodes } from "http-status-codes";
import { concatUrlByParams } from "../../../../../utils/url-generator";

const axios = require("axios");

function getAlertStructure() {
  return {
    type: null,
    message: null
  };
}

const state = () => ({
  alert: getAlertStructure(),
  promoCode: {
    searchValue: "",
    content: {}
  },
  isSentForm: false,

  cart: {},

  staticStore: {
    url: {
      apiCart: window.staticStore.urlCart,
      apiCartProduct: window.staticStore.urlCartProduct,
      apiPromoCode: window.staticStore.urlPromoCode,
      apiOrder: window.staticStore.urlOrder,
      assetImageProducts: window.staticStore.urlAssetImageProducts,
      viewProduct: window.staticStore.urlProductShow
    }
  }
});

const getters = {
  totalPrice(state, getters) {
    let totalPrice = getters.priceWithoutPromoCode;
    if (!state.promoCode.content.discount) {
      return totalPrice;
    }

    return totalPrice - (totalPrice / 100) * state.promoCode.content.discount;
  },
  priceWithoutPromoCode(state) {
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
    const url = concatUrlByParams(state.staticStore.url.apiCart, state.cart.id);
    const result = await axios.delete(url, apiConfig);

    if (result.status === StatusCodes.NO_CONTENT) {
      commit("setCart", {});
    }
  },
  async getPromoCode({ commit, getters, state }) {
    commit("cleanAlert");

    const url = state.staticStore.url.apiPromoCode + "?value=" + state.promoCode.searchValue;
    const result = await axios.get(url, apiConfig);

    if (
      result.data &&
      result.status === StatusCodes.OK &&
      result.data["hydra:member"].length
    ) {
      commit("setAlert", {
        type: "success",
        message: "Your promo code has approved!"
      });
      commit("setPromoCodeContent", result.data["hydra:member"][0]);
    } else {
      commit("setAlert", {
        type: "warning",
        message: "Entered promo code not found."
      });
    }
  },
  async makeOrder({ commit, state, dispatch }) {
    const url = state.staticStore.url.apiOrder;
    const data = {
      cartId: state.cart.id,
      promoCodeId: null
    };

    if (state.promoCode.content) {
      data.promoCodeId = state.promoCode.content.id;
    }
    const result = await axios.post(url, data, apiConfig);

    console.log(result);
    if (result.data && result.status === StatusCodes.CREATED) {
      commit("setAlert", {
        type: "success",
        message:
          "Thank you for your purchase! Our manager will contact with you in 24 hours."
      });
      commit("setIsSentForm", true);
      dispatch("cleanCart");
    }
  }
};

const mutations = {
  setCart(state, cart) {
    state.cart = cart;
  },
  setPromoCodeFormValue(state, formData) {
    state.promoCode.searchValue = formData.promoCodeValue;
  },
  setPromoCodeContent(state, promoCode) {
    state.promoCode.content = promoCode;
  },
  cleanAlert(state) {
    state.alert = getAlertStructure();
  },
  setIsSentForm(state, value) {
    state.isSentForm = value;
  },
  setAlert(state, model) {
    state.alert = {
      type: model.type,
      message: model.message
    };
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
