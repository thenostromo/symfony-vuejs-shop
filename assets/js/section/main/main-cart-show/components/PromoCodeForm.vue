<template>
  <div class="mb-2">
    <div class="row">
      <div class="col-md-8">
        <input
            v-model="form.promoCodeValue"
            type="text"
            class="form-control"
            placeholder="Promo code"
        >
      </div>
      <div class="col-md-2">
        <a
            v-if="form.promoCodeValue"
            @click="approvePromoCode"
            class="btn btn-primary"
            style="color: white;"
        >
          <span>APPROVE</span>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import {mapMutations, mapActions} from "vuex";

export default {
  name: "PromoCodeForm",
  data() {
    return {
      form: {
        promoCodeValue: ""
      }
    };
  },
  methods: {
    ...mapMutations("cart", ["setPromoCodeFormValue"]),
    ...mapActions("cart", ["getPromoCode"]),
    approvePromoCode(event) {
      event.preventDefault();
      this.setPromoCodeFormValue(this.form);
      this.getPromoCode();
    },
  }
}
</script>
