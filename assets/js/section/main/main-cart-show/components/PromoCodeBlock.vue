<template>
  <div>
    <PromoCodeForm />
    <PromoCodePrice />
  </div>
</template>

<script>
import PromoCodeForm from "../../main-cart-show/components/PromoCodeForm";
import PromoCodePrice from "../../main-cart-show/components/PromoCodePrice";

export default {
  name: "PromoCodeBlock",
  components: { PromoCodeForm, PromoCodePrice }
};
</script>
