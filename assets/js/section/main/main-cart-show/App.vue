<template>
  <div>
    <div class="row">
      <div class="col-lg-12 order-block">
        <div class="order-content">
          <Alert />
          <div v-if="showCartContent">
            <div
              v-if="cart.cartProducts.length === 0"
              class="alert alert-info mt-5"
            >
              Your cart is empty ...
            </div>
            <div v-else>
              <CartProductList />

              <PromoCodeBlock />

              <CartTotalPrice />

              <a
                class="btn btn-success mb-3"
                style="color: white;"
                @click="makeOrder"
              >
                <span>MAKE ORDER</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations, mapState } from "vuex";
import CartProductList from "./components/CartProductList";
import PromoCodeBlock from "./components/PromoCodeBlock";
import CartTotalPrice from "./components/CartTotalPrice";
import Alert from "./components/Alert";

export default {
  components: { Alert, CartTotalPrice, PromoCodeBlock, CartProductList },
  computed: {
    ...mapState("cart", ["cart", "isSentForm"]),
    showCartContent() {
      return !this.isSentForm;
    }
  },
  mounted() {
    this.getCart();
  },
  methods: {
    ...mapActions("cart", ["getCart", "makeOrder"])
  }
};
</script>

<style scoped>
.order-block {
  display: flex;
  justify-content: center;
}
.order-content {
  max-width: 700px;
}
</style>
