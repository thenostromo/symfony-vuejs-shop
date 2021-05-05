<template>
  <div class="col-6 col-md-4 col-lg-4 col-xl-3">
    <div class="product-item">
      <figure class="product-media">
        <span class="product-sale-status sale-status-new">New</span>
        <a :href="getUrlProductShow(saleCollectionProduct.product.id)">
          <img
            v-if="productImage"
            :src="getUrlProductImage(saleCollectionProduct.product, productImage)"
            :alt="saleCollectionProduct.product.title"
            :class="'product-image'"
          />
        </a>

        <div class="product-actions">
          <a href="#" class="btn-add-to-cart" @click="addToCart(saleCollectionProduct.product)">
            add to cart
          </a>
        </div>
      </figure>

      <div class="product-card-content">
        <h3 class="product-title">
          <a :href="getUrlProductShow(saleCollectionProduct.product.id)">{{ saleCollectionProduct.product.title }}</a>
        </h3>
        <div class="product-price justify-content-center">
          ${{ saleCollectionProduct.product.price }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: {
    saleCollectionProduct: {
      type: Object,
      default: () => {}
    },
  },
  computed: {
    ...mapState("saleCollection", ["staticStore"]),
    productImage() {
      return this.saleCollectionProduct.product.productImages.length
        ? this.saleCollectionProduct.product.productImages[0]
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
