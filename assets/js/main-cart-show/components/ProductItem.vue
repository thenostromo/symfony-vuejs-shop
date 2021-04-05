<template>
  <tr>
    <td class="product-col">
      <div class="text-center">
        <figure class="mr-0">
          <a
            :href="getUrlProductShow(product.id)"
            target="_blank"
          >
            <img
              :src="getUrlProductImage(product, product.image)"
              :alt="product.title"
            >
          </a>
        </figure>
        <h3 class="product-title">
          <a
            :href="getUrlProductShow(product.id)"
            target="_blank"
          >
            {{ product.title }}
          </a>
        </h3>
      </div>
    </td>
    <td class="price-col">
      ${{ product.price }}
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
        @click="removeProductFromCart(product.id)"
      >
        X
      </a>
    </td>
  </tr>
</template>

<script>
import {mapGetters, mapMutations, mapState} from "vuex";

export default {
  data() {
    return {
      quantity: 1
    }
  },
  name: "ProductItem",
  props: ['product'],
  computed: {
    ...mapGetters('cart', ['urlProductShow', 'getUrlAssetImageProducts']),
    productPrice() {
      return this.quantity * this.product.price
    }
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
