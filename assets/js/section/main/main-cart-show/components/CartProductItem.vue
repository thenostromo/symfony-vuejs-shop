<template>
  <tr>
    <td class="product-col">
      <div class="text-center">
        <figure>
          <a
            :href="getUrlProductShow(cartProduct.product.id)"
            target="_blank"
          >
            <img
              :src="getUrlProductImage(cartProduct.product, productImage)"
              :alt="cartProduct.product.title"
            >
          </a>
        </figure>
        <div class="product-title">
          <a
            :href="getUrlProductShow(cartProduct.product.id)"
            target="_blank"
          >
            {{ cartProduct.product.title }}
          </a>
        </div>
      </div>
    </td>
    <td class="price-col">
      ${{ cartProduct.product.price }}
    </td>
    <td class="quantity-col">
      <input
          v-model="quantity"
          type="number"
          class="form-control"
          min="1"
          max="10"
          step="1"
          data-decimals="0"
          required
      >
    </td>
    <td class="total-col">
      ${{ productPrice }}
    </td>
    <td class="remove-col">
      <a
        href="#"
        class="btn-remove"
        title="Remove Product"
        @click="removeCartProduct(cartProduct.id)"
      >
        X
      </a>
    </td>
  </tr>
</template>

<script>
import {mapActions, mapGetters, mapMutations, mapState} from "vuex";

export default {
  data() {
    return {
      quantity: 1
    }
  },
  name: "ProductItem",
  props: ['cartProduct'],
  computed: {
    ...mapState('cart', ['staticStore']),
    productImage() {
      return this.cartProduct.product.productImages.length
          ? this.cartProduct.product.productImages[0]
          : null;
    },
    productPrice() {
      return this.quantity * this.cartProduct.product.price
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
