<template>
  <div class="product">
    <div class="product-details">
      <h4 class="product-title">
        <a :href="getUrlProductShow(cartProduct.id)">
          {{ cartProduct.product.title }}
        </a>
      </h4>
      <span class="product-info">
        <span class="product-quality">{{ cartProduct.quantity }}</span>
        x ${{ cartProduct.product.price }}
      </span>
    </div>
    <figure class="product-image-container">
      <a
        :href="getUrlProductShow(cartProduct.product.id)"
        v-if="productImage"
      >
        <img
          :src="getUrlProductImage(cartProduct.product, productImage)"
          class="product-image"
          :alt="productImage"
        >
      </a>
    </figure>
    <a
      href="#"
      class="btn-remove"
      @click="removeCartProduct(cartProduct.id)"
    >
      X
    </a>
  </div>
</template>

<script>
import {mapGetters, mapMutations, mapState, mapActions} from "vuex";

export default {
  name: "CartProductItem",
  props: ['cartProduct'],
  computed: {
    ...mapState('cart', ['staticStore']),
    productImage() {
      return this.cartProduct.product.productImages.length
          ? this.cartProduct.product.productImages[0]
          : null;
    }
  },
  methods: {
    ...mapActions('cart', ['removeCartProduct']),
    getUrlProductShow(productId) {
      return this.staticStore.url.viewProduct + "/" + productId;
    },
    getUrlProductImage(product, image) {
      return (
          this.staticStore.url.assetImageProducts +
          "/" +
          product.id +
          "/" +
          image.filenameSmall
      );
    },
  }
}
</script>

<style scoped>

</style>
