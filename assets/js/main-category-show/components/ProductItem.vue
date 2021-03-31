<template>
  <div class="col-6 col-md-4 col-lg-4 col-xl-3">
    <div class="product text-center">
      <div class="product-content">
        <figure class="product-media">
          <span class="product-label label-new">New</span>
          <a :href="getUrlProductShow(product.id)">
            <img
                v-for="image in product.images"
                :src="getUrlProductImage(product, image)"
                :alt="product.title"
                :class="product-image"
            >
          </a>

          <div class="product-action">
            <a href="#" @click="addToCart(product)" class="btn-product btn-cart">
              <span>add to cart</span>
            </a>
          </div><!-- End .product-action -->
        </figure><!-- End .product-media -->

        <div class="product-body">
          <h3 class="product-title">
            <a :href="getUrlProductShow(product.id)">{{ product.title }}</a>
          </h3><!-- End .product-title -->
          <div class="product-price">
            ${{ product.price }}
          </div><!-- End .product-price -->
        </div><!-- End .product-body -->
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  computed: {
    ...mapGetters('category', ['urlProductShow', 'getUrlAssetImageProducts']),
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
