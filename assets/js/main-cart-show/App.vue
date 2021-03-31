<template>
  <div>
    <div class="row">
      <div class="col-lg-12 order-block">
        <div class="order-content">
          <div
            v-if="alertInfo"
            :class="getAlertClass(alertInfo.type)"
          >
            {{ alertInfo.message }}
          </div>
          <div v-if="!isSentForm">
            <div
                class="alert alert-info mt-5"
                v-if="cartProducts.length === 0"
            >
              Your cart is empty ...
            </div>
            <div v-else>
              <ProductList/>

              <div class="mb-2">
                <div class="row">
                  <div class="col-md-8">
                    <input
                        v-model="promoCodeEntered"
                        type="text"
                        class="form-control"
                        placeholder="Promo code"
                    >
                    <a
                        v-if="promoCodeEntered"
                        @click="approvePromoCode"
                        class="btn btn-primary mb-3"
                        style="color: white;"
                    >
                      <span>APPROVE</span>
                    </a>
                  </div>
                  <div class="col-md-2">
                  </div>
                </div>
              </div>

              <div class="mb-2" v-if="promoCodeInfo.discount">
                <div class="row">
                  <div class="col-md-8">
                    <strong>- {{ promoCodeInfo.discount }}%</strong> by promo code
                    <strong>"{{ promoCodeInfo.value }}"</strong>
                  </div>
                </div>
              </div>

              <div class="mb-2">
              <span style="font-size: 20px">
                Total: <strong>${{ totalPrice }}</strong>
                <span
                    v-if="discountAmount"
                    style="color: red"
                >
                  (you saved ${{ discountAmount }})
                </span>
              </span>
              </div>

              <a
                  @click="makeOrder"
                  class="btn btn-primary mb-3"
                  style="color: white;"
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
import {mapActions, mapGetters, mapMutations, mapState} from "vuex";
import ProductList from "./components/ProductList";

export default {
  data() {
    return {
      promoCode: ''
    }
  },
  components: {ProductList},
  mounted() {
    this.getProductsOfCart()
  },
  computed: {
    ...mapState('cart', ['cartProducts', 'alertInfo', 'promoCodeInfo', 'isSentForm']),
    ...mapGetters('cart', ['totalPrice', 'priceWithoutPromoCode']),
    discountAmount() {
      return Math.round((this.priceWithoutPromoCode - this.totalPrice) * 100) / 100
    },
    promoCodeEntered: {
      get() {
        return this.promoCodeInfo.value
      },
      set(value) {
        this.setPromoCodeInfo({value})
      }
    },
  },
  methods: {
    ...mapActions('cart', ['getProductsOfCart', 'makeOrder', 'approvePromoCode']),
    ...mapMutations('cart', ['cleanCart', 'setPromoCodeInfo']),
    getAlertClass(type) {
      return 'alert alert-' + type
    }
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
