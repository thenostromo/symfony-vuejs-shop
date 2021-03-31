<template>
  <div class="dropdown cart-dropdown">
    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
      <i class="fas fa-shopping-basket"></i>
      <span class="cart-count">{{ countCartProducts }}</span>
    </a>

    <div class="dropdown-menu dropdown-menu-right">
      <ProductList/>

      <div class="dropdown-cart-total">
        <span>Total</span>

        <span class="cart-total-price">${{ totalPrice }}</span>
      </div>

      <div class="dropdown-cart-action">
        <a
          :href="urlCartIndex"
          class="btn btn-primary"
        >
          View Cart
        </a>
        <a
          href="#"
          class="btn btn-outline-primary-2"
          @click="cleanCart"
        >
          <span>Checkout</span>
        </a>
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
