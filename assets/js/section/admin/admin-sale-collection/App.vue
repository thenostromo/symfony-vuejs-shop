<template>
  <div class="table-additional-selection">
    <SaleCollectionProductAdd/>

    <hr/>

    <SaleCollectionProductItem
      v-for="(product, index) in saleCollectionProducts"
      :sale-collection-product="product"
      :index="index"
      :key="product.id"
    />
  </div>
</template>

<script>
import SaleCollectionProductItem from "./components/SaleCollectionProductItem";
import SaleCollectionProductAdd from "./components/SaleCollectionProductAdd";
import {mapActions, mapGetters, mapState} from "vuex";

export default {
  components: {SaleCollectionProductAdd, SaleCollectionProductItem},
  computed: {
    ...mapState('products', ['saleCollectionProducts'])
  },
  mounted() {
    this.getCategories();
    this.getProductsBySaleCollection()
  },
  methods: {
    ...mapActions('products', ['getCategories', 'getProductsBySaleCollection']),
  }
};
</script>
