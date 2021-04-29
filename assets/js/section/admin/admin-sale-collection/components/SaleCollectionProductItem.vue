<template>
  <div class="row mb-1">
    <div class="col-md-1 text-center">
      {{ rowNumber }}
    </div>
    <div class="col-md-2">
      {{ category }}
    </div>
    <div class="col-md-4">
      {{ productTitle }}
    </div>
    <div class="col-md-2">{{ saleCollectionProduct.pricePerOne }}$</div>
    <div class="col-md-3">
      <button class="btn btn-outline-info" @click="viewDetails">
        Details
      </button>
      <button class="btn btn-outline-success" @click="remove">
        Remove
      </button>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { getUrlProductView } from "../utils/url-generator";
import { getProductInformativeTitle } from "../utils/title-formatter";

export default {
  name: "SaleCollectionProductItem",
  props: {
    saleCollectionProduct: {
      type: Object,
      default: () => {}
    },
    index: {
      type: Number,
      default: 0
    }
  },
  computed: {
    ...mapState("products", ["categories", "staticStore"]),
    rowNumber() {
      return this.index + 1;
    },
    productTitle() {
      return getProductInformativeTitle(this.saleCollectionProduct.product);
    },
    category() {
      const category = this.categories.find(
          item => item.id === this.saleCollectionProduct.product.category.id
      );
      return category ? category.title : null;
    }
  },
  methods: {
    ...mapActions("products", ["removeProduct", "getProductsBySaleCollection"]),
    viewDetails(event) {
      event.preventDefault();

      const url = getUrlProductView(
          this.staticStore.url.viewProduct,
          this.saleCollectionProduct.product.id
      );
      window.open(url, "_blank").focus();
    },
    remove(event) {
      event.preventDefault();
      this.removeProduct(this.saleCollectionProduct.id);
    }
  }
};
</script>
