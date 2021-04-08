<template>
  <div class="product-list mb-3">
    <div class="row justify-content-center">

      <ProductItem
        v-for="product in categoryProducts"
        :product="product"
        :key="product.id"
      />

      <div class="alert" v-if="!countOfProducts">
        There are no products in this category yet ...
      </div>

    </div>
  </div>
</template>

<script>
import {mapState} from "vuex";
import ProductItem from "./ProductItem";

export default {
  components: {ProductItem},
  computed: {
    ...mapState('category', ['categoryProducts', 'countOfProducts'])
  }
};
</script>
