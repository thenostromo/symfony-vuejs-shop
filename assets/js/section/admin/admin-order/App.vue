<template>
  <div class="table-additional-selection">
    <OrderProductAdd />

    <hr />

    <OrderProductItem
      v-for="(orderProduct, index) in orderProducts"
      :key="orderProduct.id"
      :order-product="orderProduct"
      :index="index"
    />

    <hr />

    <TotalPriceBlock />
  </div>
</template>

<script>
import OrderProductItem from "./components/OrderProductItem";
import OrderProductAdd from "./components/OrderProductAdd";
import { mapActions, mapState } from "vuex";
import TotalPriceBlock from "./components/TotalPriceBlock";

export default {
  components: { TotalPriceBlock, OrderProductAdd, OrderProductItem },
  computed: {
    ...mapState("products", ["staticStore", "orderProducts"])
  },
  mounted() {
    if (this.staticStore.promoCodeId) {
      this.getPromoCode();
    }
    this.getCategories();
    this.getProductsByOrder();
  },
  methods: {
    ...mapActions("products", [
      "getPromoCode",
      "getCategories",
      "getProductsByOrder"
    ])
  }
};
</script>
