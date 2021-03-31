<template>
  <div class="products mb-3">
    <div class="row">

      <ProductItem
        v-for="product in categoryProducts"
        :product="product"
        :key="product.id"
      />

    </div>
  </div>
</template>

<script>
import {mapState} from "vuex";
import ProductItem from "./ProductItem";

export default {
  components: {ProductItem},
  computed: {
    ...mapState('category', ['categoryProducts']),
  }
};
</script>
