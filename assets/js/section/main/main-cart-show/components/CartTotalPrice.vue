<template>
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
</template>

<script>
import {mapState, mapGetters} from "vuex";

export default {
  name: "CartTotalPrice",
  computed: {
    ...mapState('cart', ['promoCode']),
    ...mapGetters('cart', ['totalPrice', 'priceWithoutPromoCode']),
    discountAmount() {
      return Math.round((this.priceWithoutPromoCode - this.totalPrice) * 100) / 100
    },
  }
}
</script>
