<template>
  <div class="product">
    <div class="product-cart-details">
      <h4 class="product-title">
        <a :href="getUrlProductShow(product.id)">
          {{ product.title }}
        </a>
      </h4>
      <span class="cart-product-info">
        <span class="cart-product-qty">{{ product.quantity }}</span>
        x ${{ product.price }}
      </span>
    </div>
    <figure class="product-image-container">
      <a
        :href="getUrlProductShow(product.id)"
        class="product-image"
        v-if="product.image"
      >
        <img
          :src="getUrlProductImage(product, product.image)"
          :alt="product.title"
        >
      </a>
    </figure>
    <a
      href="#"
      class="btn-remove"
      title="Remove Product"
      @click="removeProductFromCart(product.id)"
    >
      X
    </a>
  </div>
</template>

<script>
import {mapGetters, mapMutations, mapState} from "vuex";

export default {
  name: "ProductItem",
  props: ['product'],
  computed: {
    ...mapGetters('cart', ['urlProductShow', 'getUrlAssetImageProducts']),
  },
  methods: {
    ...mapMutations('cart', ['removeProductFromCart']),
    getUrlProductImage(product, imageFilename) {
      return this.getUrlAssetImageProducts + "/" + product.id + "/" + imageFilename
    },
    getUrlProductShow(productId) {
      return this.urlProductShow + "/" + productId
    },
  }
}
</script>

<style scoped>

</style>
