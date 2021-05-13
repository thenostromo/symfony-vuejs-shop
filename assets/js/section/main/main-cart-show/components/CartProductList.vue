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
    <CartProductItem
        v-for="cartProduct in cart.cartProducts"
        :cart-product="cartProduct"
        :key="cartProduct.id"
    />
    </tbody>
  </table>
</template>

<script>
import CartProductItem from "../../main-cart-show/components/CartProductItem";
import {mapState} from "vuex";

export default {
  name: "CartProductList",
  components: {CartProductItem},
  computed: {
    ...mapState('cart', ['cart']),
  }
}
</script>
