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
  </div>
</template>

<script>
import OrderProductItem from "./components/OrderProductItem";
import OrderProductAdd from "./components/OrderProductAdd";
import { mapActions, mapState } from "vuex";

export default {
  components: { OrderProductAdd, OrderProductItem },
  computed: {
    ...mapState("products", ["orderProducts"])
  },
  mounted() {
    this.getCategories();
    this.getProductsByOrder();
  },
  methods: {
    ...mapActions("products", ["getCategories", "getProductsByOrder"])
  }
};
</script>
