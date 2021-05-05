<template>
  <div class="products">
    <CartProductItem
      v-for="cartProduct in cart.cartProducts"
      :cart-product="cartProduct"
      :key="cartProduct.id"
    />
  </div>
</template>

<script>
import CartProductItem from "../../menu-cart/components/CartProductItem";
import {mapState} from "vuex";

export default {
  name: "CartProductItem.vue",
  components: {CartProductItem},
  computed: {
    ...mapState('cart', ['cart']),
  }
}
</script>

<style scoped>

</style>
