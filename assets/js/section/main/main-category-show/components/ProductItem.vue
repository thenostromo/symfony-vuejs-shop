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
          >
        </a>

        <div class="product-actions">
          <a href="#" @click="addToCart(product)" class="btn-add-to-cart">
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
import {mapGetters} from "vuex";

export default {
  computed: {
    ...mapGetters('category', ['urlProductShow', 'getUrlAssetImageProducts']),
    productImage() {
      let productImages = []

      if (this.product._embedded && this.product._embedded.productImages) {
        productImages = this.product._embedded.productImages
      }

      return productImages.length ? productImages[0] : null
    }
  },
  props: ['product'],
  methods: {
    getUrlProductShow(productId) {
      return this.urlProductShow + "/" + productId
    },
    getUrlProductImage(product, image) {
      return this.getUrlAssetImageProducts + "/" + product.id + "/" + image.filenameMiddle
    },
    addToCart (product) {
      addProductToCart(product)
    },
  }
};
</script>
