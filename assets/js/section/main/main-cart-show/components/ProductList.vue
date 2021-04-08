<template>
  <table class="table table-cart table-mobile main-cart-show">
    <thead>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Total</th>
      <th></th>
    </tr>
    </thead>

    <tbody>
    <ProductItem
        v-for="product in cartProducts"
        :product="product"
        :key="product.id"
    />
    </tbody>
  </table>
</template>

<script>
import ProductItem from "../../main-cart-show/components/ProductItem";
import {mapState} from "vuex";

export default {
  name: "ProductList.vue",
  components: {ProductItem},
  computed: {
    ...mapState('cart', ['cartProducts']),
  }
}
</script>
