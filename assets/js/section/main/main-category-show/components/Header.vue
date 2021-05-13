<template>
  <div class="row">
    <div class="col-lg-12 mb-2">
      <div class="page-title2">
        <a class="page-title title-gradient-1">{{ category.title }}</a>
      </div>
    </div>
  </div>
</template>

<script>
import {mapGetters, mapState} from "vuex";

export default {
  computed: {
    ...mapState("category", [
      "category",
    ]),
  }
};
</script>
