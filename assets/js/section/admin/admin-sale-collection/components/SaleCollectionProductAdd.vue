<template>
  <div class="row mb-2">
    <div class="col-md-3">
      <select
        v-model="form.category"
        name="add_product_category_select"
        class="form-control"
        @change="getProducts()"
      >
        <option value="" disabled>- choose option -</option>
        <option
          v-for="category in categories"
          :key="category.id"
          :value="category.id"
        >
          {{ category.title }}
        </option>
      </select>
    </div>
    <div v-if="form.category" class="col-md-4">
      <select
        v-model="form.productId"
        name="add_product_product_select"
        class="form-control"
      >
        <option value="" disabled>- choose option -</option>
        <option
          v-for="freeProduct in freeCategoryProducts"
          :key="freeProduct.id"
          :value="freeProduct.id"
        >
          {{ productTitle(freeProduct) }}
        </option>
      </select>
    </div>
    <div v-if="form.productId" class="col-md-2">
      <input
        v-model="form.pricePerOne"
        type="number"
        class="form-control"
        placeholder="price"
        step="0.01"
        min="0"
        :max="productPriceMax"
      />
    </div>
    <div class="col-md-3">
      <button
        v-if="form.productId"
        class="btn btn-outline-info"
        @click="viewDetails"
      >
        Product Details
      </button>
      <button
        v-if="form.productId"
        class="btn btn-outline-success"
        @click="submit"
      >
        Add
      </button>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions, mapMutations } from "vuex";
import { getUrlProductView } from "../utils/url-generator";
import { getProductInformativeTitle } from "../utils/title-formatter";

export default {
  name: "SaleCollectionProductAdd",
  data() {
    return {
      form: {
        category: "",
        productId: "",
        pricePerOne: ""
      },
    };
  },
  computed: {
    ...mapState("products", ["categories", "staticStore", "newProduct"]),
    ...mapGetters("products", ["freeCategoryProducts"]),
    productPriceMax() {
      const productData = this.freeCategoryProducts.find(
          product => product.id === this.form.productId
      );
      return parseInt(productData.price);
    },
    productQuantityMax() {
      const productData = this.freeCategoryProducts.find(
          product => product.id === this.form.productId
      );
      return parseInt(productData.quantity);
    }
  },
  methods: {
    ...mapMutations("products", ["setNewProductInfo"]),
    ...mapActions("products", [
      "getProductsByCategory",
      "addNewProduct",
      "getProductsBySaleCollection"
    ]),
    productTitle(product) {
      return getProductInformativeTitle(product);
    },
    getProducts() {
      this.setNewProductInfo(this.form);
      this.getProductsByCategory();
    },
    viewDetails(event) {
      event.preventDefault();

      const url = getUrlProductView(
          this.staticStore.url.viewProduct,
          this.form.productId
      );
      window.open(url, "_blank").focus();
    },
    submit(event) {
      event.preventDefault();
      this.setNewProductInfo(this.form);
      this.addNewProduct();
      this.resetData();
    },
    resetData() {
      Object.assign(this.$data, this.$options.data.apply(this));
    }
  }
};
</script>

<style scoped></style>
