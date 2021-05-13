<template>
  <div class="product-list mb-3">
    <div class="row justify-content-center">

      <ProductItem
        v-for="saleCollectionProduct in saleCollectionProducts"
        :sale-collection-product="saleCollectionProduct"
        :key="saleCollectionProduct.id"
      />

      <div class="alert" v-if="!saleCollectionProductsTotalCount">
        There are no products in this sale collection yet ...
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
    ...mapState('saleCollection', ['saleCollectionProducts', 'saleCollectionProductsTotalCount'])
  }
};
</script>
