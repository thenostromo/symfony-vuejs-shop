<template>
  <div class="dropdown-cart-products">
    <ProductItem
      v-for="product in cartProducts"
      :product="product"
      :key="product.id"
    />
  </div>
</template>

<script>
import ProductItem from "../../menu-cart/components/ProductItem";
import {mapState} from "vuex";

export default {
  name: "ProductList.vue",
  components: {ProductItem},
  computed: {
    ...mapState('cart', ['cartProducts']),
  }
}
</script>

<style scoped>

</style>
