<template>
  <div class="dropdown cart-dropdown">
    <a href="#" class="cart-dropdown-btn-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
      <i class="fas fa-shopping-cart"></i>
      <span class="count">{{ countCartProducts }}</span>
    </a>

    <div class="dropdown-menu cart-dropdown-window">
      <ProductList/>

      <div class="total">
        <span>Total</span>

        <span class="total-price">${{ totalPrice }}</span>
      </div>

      <div class="actions" v-if="countCartProducts">
        <a
          :href="urlCartIndex"
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
import {mapActions, mapGetters, mapMutations} from "vuex";
import ProductList from "./components/ProductList";

export default {
  components: {ProductList},
  computed: {
    ...mapGetters('cart', ['totalPrice', 'countCartProducts', 'urlCartIndex']),
  },
  mounted() {
    this.getProductsOfCart()
  },
  methods: {
    ...mapActions('cart', ['getProductsOfCart']),
    ...mapMutations('cart', ['cleanCart'])
  }
};
</script>
