<template>
  <div class="table-additional-selection">
    <AddProduct/>

    <hr/>

    <ProductItem
      v-for="(product, index) in orderProducts"
      :product="product"
      :index="index"
      :key="product.id"
    />
  </div>
</template>

<script>
import ProductItem from "./components/ProductItem";
import AddProduct from "./components/AddProduct";
import {mapActions, mapGetters, mapState} from "vuex";

export default {
  components: {AddProduct, ProductItem},
  mounted() {
    this.getProductsByOrder()
  },
  computed: {
    ...mapState('products', ['orderProducts'])
  },
  methods: {
    ...mapActions('products', ['getProductsByOrder']),
  }
};
</script>
