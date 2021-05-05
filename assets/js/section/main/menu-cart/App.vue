<template>
  <div class="dropdown cart-dropdown">
    <a href="#" class="cart-dropdown-btn-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
      <i class="fas fa-shopping-cart"></i>
      <span class="count">{{ countCartProducts }}</span>
    </a>

    <div class="dropdown-menu cart-dropdown-window">
      <CartProductList/>

      <div class="total">
        <span>Total</span>

        <span class="total-price">${{ totalPrice }}</span>
      </div>

      <div class="actions" v-if="countCartProducts">
        <a
          :href="staticStore.url.viewCart"
          class="btn btn-success"
        >
          View Cart
        </a>
        <a
          href="#"
          class="btn btn-cancel mt-2"
          @click="cleanCart"
        >
          Checkout
        </a>
      </div>
      <div class="text-center" v-else>
        Your cart is empty.
      </div>
    </div>
  </div>
</template>

<script>
import {mapState, mapActions, mapGetters, mapMutations} from "vuex";
import CartProductList from "./components/CartProductList";

export default {
  components: {CartProductList},
  computed: {
    ...mapState("cart", ["staticStore"]),
    ...mapGetters('cart', ['totalPrice', 'countCartProducts']),
  },
  mounted() {
    this.getCart()
  },
  methods: {
    ...mapActions('cart', ['getCart', 'cleanCart'])
  }
};
</script>
