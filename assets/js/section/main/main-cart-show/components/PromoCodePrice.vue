<template>
  <div v-if="promoCode.content.discount" class="mb-2">
    <div class="row">
      <div class="col-md-8">
        <strong>- {{ promoCode.content.discount }}%</strong> by promo code
        <strong>"{{ promoCode.content.value }}"</strong>
      </div>
    </div>
  </div>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "PromoCodePrice",
  computed: {
    ...mapState('cart', ['promoCode']),
  }
}
</script>
