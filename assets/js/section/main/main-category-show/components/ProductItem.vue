<template>
  <div class="col-6 col-md-4 col-lg-4 col-xl-3">
    <div class="product-item">
      <figure class="product-media">
        <span class="product-sale-status sale-status-new">New</span>
        <a :href="getUrlProductShow(product.id)">
          <img
            v-if="productImage"
            :src="getUrlProductImage(product, productImage)"
            :alt="product.title"
            :class="'product-image'"
          />
        </a>

        <div class="product-actions">
          <a href="#" class="btn-add-to-cart" @click="addToCart(product)">
            add to cart
          </a>
        </div>
      </figure>

      <div class="product-card-content">
        <h3 class="product-title">
          <a :href="getUrlProductShow(product.id)">{{ product.title }}</a>
        </h3>
        <div class="product-price justify-content-center">
          ${{ product.price }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: {
    product: {
      type: Object,
      default: () => {}
    },
  },
  computed: {
    ...mapState("category", ["staticStore"]),
    productImage() {
      return this.product.productImages.length
        ? this.product.productImages[0]
        : null;
    }
  },
  methods: {
    getUrlProductShow(productId) {
      return this.staticStore.url.viewProduct + "/" + productId;
    },
    getUrlProductImage(product, image) {
      return (
        this.staticStore.url.assetImageProducts +
        "/" +
        product.id +
        "/" +
        image.filenameMiddle
      );
    },
    addToCart(product) {
      window.addProductToCart(product);
    }
  }
};
</script>
