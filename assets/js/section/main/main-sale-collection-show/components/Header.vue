<template>
  <div class="row">
    <div class="col-lg-12 mb-2">
      <div class="page-title2">
        <a class="page-title title-gradient-1">{{ saleCollection.title }}</a>
        <a class="page-subtitle mt-2">Only until {{ untilDateFormatted }}</a>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters, mapState} from "vuex";

export default {
  computed: {
    ...mapState("saleCollection", [
      "saleCollection",
    ]),
    untilDateFormatted() {
      const date = new Date(this.saleCollection.validUntil);
      return date.getDay() + "." + date.getMonth() + "." + date.getFullYear();
    }
  }
};
</script>
